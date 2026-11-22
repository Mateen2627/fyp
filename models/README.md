# Bundled face models

These are exact copies of the pretrained files used by the working development installation, not models trained on the project's students. Student photos and face templates are not part of this bundle.

The files live in `.deepface/weights/` under this directory. `main.py` sets `DEEPFACE_HOME` to this `models/` directory before importing DeepFace, and selects the TensorFlow backend. DeepFace appends `.deepface/weights` to that path. No user-specific model directory or first-run model download is needed.

| File | Purpose | Bytes |
| --- | --- | ---: |
| `facenet512_weights.h5` | FaceNet512 recognition | 94,955,648 |
| `2.7_80x80_MiniFASNetV2.h5` | Anti-spoofing model V2 | 2,234,368 |
| `4_0_0_80x80_MiniFASNetV1SE.h5` | Anti-spoofing model V1SE | 2,288,392 |
| `haarcascade_frontalface_default.xml` | OpenCV face detector | 930,127 |
| `haarcascade_eye.xml` | OpenCV eye detector/alignment | 341,406 |

Total: **100,749,941 bytes (about 96.1 MiB)**. All files are included directly in Git; Git LFS is not required. The largest file is about 90.6 MiB. `.gitattributes` disables line-ending conversion for every model file, including the XML files, to preserve their checksums.

## Verification and restoration

Run this from the project root, using any Python 3.12 interpreter:

```sh
python face_models.py
```

[manifest.json](manifest.json) records each file's byte length, SHA-256 checksum, and upstream download URL, as well as the recognition configuration. Startup verifies the bundle before loading the models and fails clearly if a file is missing or changed.

To recover missing files, restore `models/` from Git. Upstream URLs in the manifest are additional provenance and recovery sources; check hashes after downloading replacements. The bundle does not need network access to load. Installing the Python packages still requires their distributions.

## Upstream sources and notices

- FaceNet512: [DeepFace model distribution](https://github.com/serengil/deepface_models) by Sefik Ilkin Serengil, based on [David Sandberg's FaceNet](https://github.com/davidsandberg/facenet). Retained notices: [DeepFace models MIT](licenses/deepface-models-MIT.txt) and [FaceNet MIT](licenses/facenet-MIT.txt).
- MiniFASNet: TensorFlow weight distributions from DeepFace, derived from [Silent-Face-Anti-Spoofing](https://github.com/minivision-ai/Silent-Face-Anti-Spoofing) by Minivision. Retained notices: DeepFace models MIT and [Minivision Apache 2.0](licenses/silent-face-Apache-2.0.txt). The weight files are unmodified copies of the DeepFace TensorFlow distributions.
- Haar cascades: [OpenCV 4.9.0](https://github.com/opencv/opencv/tree/4.9.0/data/haarcascades). Their Intel/OpenCV license notices and creator attributions remain embedded at the start of each XML file.

The application's root license does not replace these notices. Keep the files, manifest, and notices together when redistributing the bundle.
