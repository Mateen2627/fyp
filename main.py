from fastapi import FastAPI, UploadFile, File, HTTPException
from deepface import DeepFace
from pathlib import Path
from tempfile import TemporaryDirectory
from threading import Lock
import logging
import math

app = FastAPI(title="NUML Face Verification")
logger = logging.getLogger(__name__)
MAX_IMAGE_BYTES = 5 * 1024 * 1024
FACE_THRESHOLD = 0.25
verification_lock = Lock()


@app.get("/")
def root():
    return {
        "service": "NUML Face Verification",
        "status": "running",
        "health": "/health",
        "verification_endpoint": "POST /verify-face",
    }


def save_upload(upload: UploadFile, destination: Path):
    # Fixed server filenames avoid using multipart filenames as filesystem paths.
    size = 0
    with destination.open("wb") as buffer:
        while chunk := upload.file.read(64 * 1024):
            size += len(chunk)
            if size > MAX_IMAGE_BYTES:
                raise HTTPException(status_code=413, detail="Each image must be at most 5 MB.")
            buffer.write(chunk)
    if size == 0:
        raise HTTPException(status_code=422, detail="Both images must contain image data.")


@app.get("/health")
def health():
    # Health confirms the API is running; models may still need their first load.
    return {"status": "ok"}


@app.post("/verify-face")
def verify_face(
    reference_image: UploadFile = File(...),
    live_image: UploadFile = File(...)
):
    try:
        with TemporaryDirectory(prefix="numl-face-") as directory:
            ref_path = Path(directory) / "reference.jpg"
            live_path = Path(directory) / "live.jpg"
            save_upload(reference_image, ref_path)
            save_upload(live_image, live_path)
            with verification_lock:
                result = DeepFace.verify(
                    img1_path=str(ref_path),
                    img2_path=str(live_path),
                    model_name="Facenet512",
                    distance_metric="cosine",
                    anti_spoofing=True,
                    enforce_detection=True,
                    threshold=FACE_THRESHOLD,
                )
            distance = float(result["distance"])
            threshold = float(result["threshold"])
            if not math.isfinite(distance) or not math.isfinite(threshold) or distance < 0:
                raise RuntimeError("Invalid verification metrics")
            if not math.isclose(threshold, FACE_THRESHOLD, rel_tol=0, abs_tol=1e-6):
                raise RuntimeError("Unexpected verification threshold")
            return {
                "verified": bool(result["verified"]) and distance <= threshold,
                "distance": distance,
                "threshold": threshold,
            }
    except HTTPException:
        raise
    except ValueError as error:
        # DeepFace uses ValueError for no detected face and detected spoofing.
        return {"verified": False, "error": str(error)}
    except Exception:
        logger.exception("Face verification failed")
        raise HTTPException(status_code=503, detail="Face verification is temporarily unavailable.")
    finally:
        reference_image.file.close()
        live_image.file.close()
