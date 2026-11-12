# NUML LMS workflow

Apply the new schema and seed the specified staff roles:

```sh
php artisan migrate
php artisan db:seed
php artisan lms:secure-attachments
```

The last command moves any existing assignment/quiz files and submissions from public storage to private storage, verifying the copies before removing public originals. New work is stored privately automatically. No AI face model is installed by these changes.

## Staff access

| Staff member | Username | Departments | Responsibility |
| --- | --- | --- | --- |
| Ma'am Neelofer | `neelofer` | CS, AI | HOD |
| Dr Rao Kashif | `rao` | IT, SE | HOD |
| M Zainulabideen | `zainulabideen` | CS, AI | Coordinator |
| Mustajeeb ur Rehman | `mustajeeb` | IT, SE | Coordinator |

Seeded passwords remain `cj`. English, Psychology, and Management Sciences retain sample HOD/coordinator appointments until their actual staff are supplied. Department memberships and leadership flags are stored in `department_staff`; the old settings entries are retained for compatibility. HODs/coordinators are still teachers and can teach their own assigned subjects.

| Action | Administrator | HOD | Coordinator | Subject teacher | Student |
| --- | --- | --- | --- | --- | --- |
| Add/edit/remove active teachers and students | Yes | No | No | No | No |
| Move a student to a semester and matching section | Yes | No | No | No | No |
| Upload/replace official face reference photos | Yes | No | Own departments | No | No |
| Maintain semester, section and subject catalog | Yes | No | No | No | No |
| Assign/reassign/revoke subject teachers | No | Own departments | No | No | No |
| Create/edit/delete timetable lessons and slots | No | No | Own departments | No | No |
| View department class progress, work, attendance and marks | No department editing | Own departments, read only | Own departments, read only | Assigned subjects/sections | Own work/attendance/marks |
| Publish assignments and quizzes | No | Only as assigned subject teacher | Only as assigned subject teacher | Assigned subjects/sections | No |
| Submit an assignment or quiz | No | No | No | No | Own current class and section |
| Grade submissions / enter subject marks | No | Only as assigned subject teacher | Only as assigned subject teacher | Assigned subjects/sections | No |
| Submit a face attendance capture | No | No | No | No | Own scheduled lesson |

These rules are checked on the server. Older duplicate school-management routes cannot bypass them. Public registration is disabled because administrators manage university accounts. Super admins follow the same HOD/coordinator boundaries for academic work.

## Normal operation

1. An administrator creates teacher accounts and selects their departments. Student accounts require a semester, a section belonging to that semester, an admission number, session, and year admitted. Parent accounts are optional.
2. The department HOD opens **Assign Subject Teachers**, selects a semester, subject, section (or all sections), and a teacher belonging to that department. Duplicate or overlapping allocations are rejected. Replacing a teacher for the same subject/class/section transfers responsibility for existing assignments and quizzes, preserving submissions and grades. An allocation with existing work cannot be moved to a different subject, semester, or section.
3. The department coordinator opens **Manage Timetables**, creates a timetable for a semester and section, adds time slots, and schedules subjects with assigned teachers. The server rejects section overlaps, teacher double bookings, mismatched subjects/slots, and cross-department edits. A teacher reassignment that introduces a timetable clash is also rejected.
4. Subject teachers publish assignments/quizzes using their assigned subject/class/section pairs. Students see the relevant work, submit text or a PDF, and view the teacher's marks and feedback. Assignment deadlines and quiz opening times are enforced. Duplicate submissions are rejected. Marks cannot exceed the activity's total marks.
5. HODs and coordinators open **Department Progress** to inspect each semester/section, attendance, pending check-ins, assignments, quizzes, responses, and subject marks. These pages have no write actions. Their personal teaching tools remain separate.
6. Coordinators open **Manage Student Reference Photos** from Department Progress to upload or replace official student photos in their own departments. Administrators can also upload a photo while creating or editing a student. Students may change their profile photo, but cannot change the official face reference.
7. Administrators change student placement through **Manage Users → Edit**. Both semester and section are validated together. Removing an account archives access and preserves academic history. A serving HOD/coordinator must have their responsibilities reassigned before their account can be removed.

Teacher assignments support a specific section or all sections. Existing class-wide assignments continue working. The legacy subject's teacher field remains a display reference; authorization and section-specific marks use the actual teaching assignments.

## Facial attendance integration

Students open **My Attendance**, choose a scheduled lesson, enable the camera, and submit a capture. Camera access requires HTTPS or localhost and browser permission. The server determines the student, class, section, subject, date, and attendance window; browser-provided identities, verification flags, statuses, and confidence scores do not establish attendance. The check-in window starts 15 minutes before class and ends when class finishes. One check-in is allowed per student/lesson/date.

The Laravel app sends the authenticated student's protected official photo and live capture to the FastAPI service at `FACE_SERVICE_URL` (default `http://127.0.0.1:8001/verify-face`). Set this URL in `.env` if your service listens elsewhere. Start the service from the project directory with:

```powershell
.\venv\Scripts\python.exe -m uvicorn main:app --host 127.0.0.1 --port 8001
```

The service accepts multipart fields `reference_image` and `live_image`, runs DeepFace `Facenet512` with anti-spoofing and threshold `0.25`, and responds with `verified`, `distance`, and `threshold`. Laravel records attendance only when `verified` is the boolean `true`, the threshold matches the configured value, and the distance is within the threshold. Mismatches, spoof results, inconsistent responses, and service errors do not record attendance. Reference photos are served through authenticated coordinator/admin routes; the generic public-storage route blocks the reference-photo directory. Captures are stored on the private local disk and are not publicly downloadable.

The teacher's old simulated facial-attendance endpoint and manual attendance writes are disabled. Attendance displays distinguish pending check-ins from recorded attendance. Counts refer to recorded entries; they do not assume an unpublished semester calendar or manufacture absences.

The default application timezone is `Asia/Karachi`; `APP_TIMEZONE` can override it.

## Seeding and verification

Seeding initializes sample teaching assignments once per course. Later HOD allocations/revocations and administrator student placements are retained. Archived seeded accounts are not restored. Existing active seeded accounts have their password reset to `cj`; unrelated accounts retain their passwords. The named HOD/coordinator mappings are reapplied by the demo seed.

Run the integration checks without changing your configured database:

```sh
php tests/verify_numl_seed.php
php tests/verify_lms_workflow.php
php tests/verify_face_attendance.php
.\venv\Scripts\python.exe -m unittest discover -s tests -p test_face_service.py -v
```

They use SQLite in memory and temporary file storage. The workflow checks send requests through Laravel's HTTP kernel, render screens, and exercise allowed and forbidden operations. Face verification integration tests fake the HTTP response from the local service; they do not establish real-world recognition accuracy. The Python tests exercise the FastAPI contract without loading model weights.
