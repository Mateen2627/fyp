# NUML FYP seed data

From the project directory, run:

```sh
php artisan migrate
php artisan db:seed
```

If migrations are already current, only `php artisan db:seed` is needed. No schema changes were added for this dataset.

## What is created

- Seven departments: Computer Science, Software Engineering, Information Technology, Artificial Intelligence, English, Psychology, and Management Sciences.
- Eight semester classes per department (56 classes), each with active sections A and B (112 sections).
- Ten students per section (1,120 students), each with a unique admission number and a student record matching their section and semester.
- Thirty teachers, including all six supplied faculty names, plus a super admin, admin, and accountant.
- One sample HOD and coordinator per department, and a teacher as class incharge for every section. These staff members also teach subjects.
- Both subjects and courses, with matching teacher assignments, so the legacy marks screens and newer teacher/student course screens have data.
- Session `2023-2027`, NUML system settings, Pakistani provinces/districts and nationalities, blood groups, generic demo grades, and sample hostels.

## Login credentials

Every account created by these seeders has password **`cj`**, stored using Laravel hashing.

| Account | Username on an empty database |
| --- | --- |
| Super admin | `cj` |
| Admin | `admin` |
| Accountant, Hamza Akram | `hamza` |
| Mr Faisal Hussain | `faisal` |
| Dr Rao Kashif | `rao` |
| Ma'am Neelofer | `neelofer` |
| Mr Kashif | `kashif` |
| M Zainulabideen | `zainulabideen` |
| Mustajeeb ur Rehman | `mustajeeb` |
| Marjan | `marjan` |
| Mateen | `mateen` |
| Mudassir | `mudassir` |
| Sheeza | `sheeza` |
| Asad Ali Arif | `asad` |
| Faiq Riaz | `faiq` |

Usernames use lowercase first names, ignoring titles and the abbreviated `M` initial. Repeated or occupied first names receive numeric suffixes: `ali`, `ali2`, `ali3`. Existing users can therefore change which suffix a new account receives. Emails use the reserved `numl.example.test` domain and are sample addresses.

## Assumptions and existing application limits

The supplied six students are placed together in **CS semester 6, section A**. This is an editable demonstration placement, not an inference about their current semester. The sample students across all eight semesters use the requested `2023-2027` session to exercise every class screen; this is not a date-derived roster of one real cohort.

CS semester 1-6 subject titles match the supplied list, including its spelling. Labs are separate subjects. Pre Math I/II retain their non-credited labels. **CS semesters 7-8, other departments' curricula, all course codes, additional people, staff appointments, and hostel names are sample data**, not verified university records. Grade boundaries retain the application's generic demo scale. Credit hours are not invented because the schema has no credit-hour column.

The existing schema is used as follows:

| University concept | Existing storage |
| --- | --- |
| Department | `class_types` |
| Department semester | `my_classes`, e.g. `CS - Semester 6` |
| Section | `sections`, e.g. `A` or `B` |
| Class incharge | `sections.teacher_id` |
| Subject teacher | `subjects.teacher_id` and `teacher_assignments` |
| HOD/coordinator appointment | JSON in `settings.description`, with keys such as `numl_hod_cs` and `numl_coordinator_cs` |

Each appointment contains `department_id`, `user_id`, `session`, and `sample`. For example, read one with `json_decode(\App\Models\Setting::where('type', 'numl_hod_cs')->value('description'), true)`. The first two faculty listed for a department are its sample HOD and coordinator. All retain `user_type = teacher`. **These settings store appointments only; the current application has no dedicated HOD/coordinator dashboard or permission system.** The teaching tables assign courses at semester-class level, shared by A/B, because they have no section-specific assignment field.

## Editing and rerunning

Edit [Support/NumlSeedData.php](Support/NumlSeedData.php) to change departments, faculty, subjects, sections, students per section, or the named students' semester. Keep stable user codes unchanged. Rerunning updates the same seeded records and resets their password to `cj`; it preserves unrelated users, records, and their relationships. It does not delete previous school demo data or remove seeded entries that have been removed from the catalog. Configured contact details and term dates are preserved.

`DatabaseSeeder` runs in one transaction. All seeders use natural keys instead of assuming database IDs or deleting tables. To inspect actual usernames, query `users` where `code` starts with `NUML-`.

Run the integration verification without changing your configured database:

```sh
php tests/verify_numl_seed.php
```

It uses SQLite in memory, migrates the full schema, checks the roster and credentials, seeds twice, and verifies that existing data, IDs, relationships, and usernames survive.
