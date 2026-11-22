"""Verify the bundled face models without importing machine learning libraries."""

import hashlib
import json
from pathlib import Path

MODEL_HOME = Path(__file__).resolve().parent / "models"
WEIGHTS_DIRECTORY = MODEL_HOME / ".deepface" / "weights"


def verify_model_files():
    manifest = json.loads((MODEL_HOME / "manifest.json").read_text(encoding="utf-8"))
    for artifact in manifest["files"]:
        path = WEIGHTS_DIRECTORY / artifact["filename"]
        if not path.is_file():
            raise RuntimeError(
                f"Missing bundled model: {path.name}. Restore models/ from the repository."
            )
        digest = hashlib.sha256()
        with path.open("rb") as model_file:
            for chunk in iter(lambda: model_file.read(1024 * 1024), b""):
                digest.update(chunk)
        if path.stat().st_size != artifact["bytes"] or digest.hexdigest() != artifact["sha256"]:
            raise RuntimeError(
                f"Bundled model failed its checksum: {path.name}. Restore it from the repository."
            )
    return len(manifest["files"])


if __name__ == "__main__":
    print(f"Verified {verify_model_files()} bundled model files (SHA-256).")
