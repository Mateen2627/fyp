"""Exercise the FastAPI handler without loading TensorFlow or model weights."""

import importlib.util
import io
import sys
import tempfile
import types
import unittest
from pathlib import Path
from unittest.mock import patch

from fastapi import HTTPException, UploadFile


class FaceServiceTests(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.temp_context = tempfile.TemporaryDirectory(prefix=".face-service-temp-", dir=Path(__file__).resolve().parent)
        tempfile.tempdir = cls.temp_context.name
        stub = types.ModuleType("deepface")
        stub.DeepFace = types.SimpleNamespace(verify=lambda **kwargs: None)
        spec = importlib.util.spec_from_file_location("numl_face_test_app", Path(__file__).resolve().parents[1] / "main.py")
        cls.api = importlib.util.module_from_spec(spec)
        with patch.dict(sys.modules, {"deepface": stub}):
            spec.loader.exec_module(cls.api)

    @classmethod
    def tearDownClass(cls):
        tempfile.tempdir = None
        cls.temp_context.cleanup()

    def setUp(self):
        self.reference = UploadFile(file=io.BytesIO(b"official-reference"), filename="../../untrusted.jpg")
        self.live = UploadFile(file=io.BytesIO(b"live-capture"), filename="live.jpg")
        self.paths = []

    def result(self, value=None, error=None):
        def verify(**kwargs):
            self.paths.extend([Path(kwargs["img1_path"]), Path(kwargs["img2_path"])])
            self.assertEqual(self.paths[0].read_bytes(), b"official-reference")
            self.assertEqual(self.paths[1].read_bytes(), b"live-capture")
            self.assertEqual(kwargs["model_name"], "Facenet512")
            self.assertEqual(kwargs["distance_metric"], "cosine")
            self.assertEqual(kwargs["threshold"], 0.25)
            self.assertIs(kwargs["anti_spoofing"], True)
            self.assertIs(kwargs["enforce_detection"], True)
            if error:
                raise error
            return value
        with patch.object(self.api.DeepFace, "verify", side_effect=verify), patch.object(self.api.logger, "exception"):
            return self.api.verify_face(self.reference, self.live)

    def tearDown(self):
        self.assertTrue(self.reference.file.closed)
        self.assertTrue(self.live.file.closed)
        for path in self.paths:
            self.assertFalse(path.exists(), "Temporary face image was retained")

    def test_match_contract_and_fixed_filenames(self):
        result = self.result({"verified": True, "distance": 0.1, "threshold": 0.25})
        self.assertEqual(result, {"verified": True, "distance": 0.1, "threshold": 0.25})
        self.assertEqual(self.paths[0].name, "reference.jpg")

    def test_mismatch(self):
        self.assertIs(self.result({"verified": False, "distance": 0.8, "threshold": 0.25})["verified"], False)

    def test_spoof(self):
        result = self.result(error=ValueError("Spoof detected in given image"))
        self.assertFalse(result["verified"])
        self.assertIn("Spoof", result["error"])

    def test_no_face(self):
        result = self.result(error=ValueError("Face could not be detected"))
        self.assertFalse(result["verified"])

    def test_model_failure_is_generic_service_error(self):
        with self.assertRaises(HTTPException) as error:
            self.result(error=RuntimeError("private model path"))
        self.assertEqual(error.exception.status_code, 503)
        self.assertNotIn("private", error.exception.detail)

    def test_wrong_threshold_is_rejected(self):
        with self.assertRaises(HTTPException) as error:
            self.result({"verified": True, "distance": 0.1, "threshold": 0.5})
        self.assertEqual(error.exception.status_code, 503)

    def test_nonfinite_distance_is_rejected(self):
        with self.assertRaises(HTTPException):
            self.result({"verified": True, "distance": float("nan"), "threshold": 0.25})

    def test_distance_above_threshold_cannot_verify(self):
        self.assertFalse(self.result({"verified": True, "distance": 0.26, "threshold": 0.25})["verified"])

    def test_empty_upload(self):
        self.reference.file = io.BytesIO()
        with patch.object(self.api.DeepFace, "verify") as verify:
            with self.assertRaises(HTTPException) as error:
                self.api.verify_face(self.reference, self.live)
            self.assertEqual(error.exception.status_code, 422)
            verify.assert_not_called()

    def test_oversized_upload(self):
        self.live.file = io.BytesIO(b"x" * (self.api.MAX_IMAGE_BYTES + 1))
        with patch.object(self.api.DeepFace, "verify") as verify:
            with self.assertRaises(HTTPException) as error:
                self.api.verify_face(self.reference, self.live)
            self.assertEqual(error.exception.status_code, 413)
            verify.assert_not_called()

    def test_health(self):
        self.assertEqual(self.api.health(), {"status": "ok"})
        self.assertEqual(self.api.root(), {
            "service": "NUML Face Verification",
            "status": "running",
            "health": "/health",
            "verification_endpoint": "POST /verify-face",
        })
        # Health does not consume uploads, unlike verify_face.
        self.reference.file.close()
        self.live.file.close()


if __name__ == "__main__":
    unittest.main()
