# Changelog

## Bug-fix release — 18 Oct 2026

No database changes: existing installs only need the updated files.

### Security
- **Stored XSS in the admin panel.** A student could register with HTML or JavaScript in their name or city, and it ran in the admin's browser when the admin opened their profile. Question, option and exam text in the question preview and answer-review modals had the same problem. All user-supplied values in JavaScript-built HTML are now escaped (`esc()` helper in `admin/students/index.php`, `admin/results/index.php`, `admin/exams/questions.php`).
- **Unrestricted image upload.** Any file, including `.php`, could be uploaded as a question image. Uploads are now checked as real JPG, PNG, GIF or WebP images, limited to 5 MB, and renamed with an extension taken from the detected image type (`AdminController::handleImageUpload()`). `public/uploads/.htaccess` blocks script execution in the uploads folder on Apache.
- **`scratch.php` removed.** This debug script in the web root deleted the first question in the database for anyone who opened `/scratch.php`, with no login.
- **Deactivated accounts could still log in.** Login now refuses inactive accounts with a clear message, and the session ID is regenerated on login.
- **Students could submit other students' attempts** through `/student/exam/submit?attempt_id=…`. Submissions, answer saves and the exam page now check that the attempt belongs to the logged-in student and is still in progress.

### Bugs
- **Exam start and end times were not enforced.** Students could start upcoming or already-ended exams from their dashboard or by URL. Only live exams can be started now. The dashboard shows "Starts …" or "Exam Ended" for the others, and the timer also stops at the exam's end time.
- **Examiner accounts got a 403 error after logging in.** Examiners now go to the panel with access to Dashboard, Exams, Questions and Results. Students and Users stay admin-only, in both the menu and the controller.
- **Question images never displayed.** Files were saved to `public/uploads/questions/` but linked as `/uploads/questions/`. New uploads use the correct `/public/uploads/questions/` path.
- **"My Results" link went nowhere.** It now opens a new page (`/student/results`) listing every submitted exam with score, pass/fail and integrity warnings.
- **Full-screen mode was promised but not enforced.** The exam now opens behind a full-screen gate. Leaving full screen is logged as a violation and the gate returns. Tab-switch and full-screen warnings show as an in-page banner instead of an `alert()`. One action is logged once, not twice.
- **Students page showed 0 exams taken and no average score.** The queries counted `submitted` attempts, but graded attempts are stored as `evaluated`.
- **Invisible badges and icons.** The LIVE and Negative Marking badges on the Exams page, and the stat icons on the Users page, were the same colour as their background.
- **Average score shown as a percentage.** It is an average of marks, so the `%` sign was removed on the Students, Edit Student and Users pages.
- **Result page warning wording.** It now says "proctoring warnings (tab switches or leaving full screen)" instead of "tab-switch warnings", with correct singular/plural.
- **Question palette tiles stretched to fill the sidebar** on the exam screen.
- **Homepage "Exam(s) Live" counted every published exam,** including upcoming and ended ones.
- **Admin dashboard greeting and avatar** now use the logged-in user's name ("Welcome back, Super Admin!").

### Database
- **`database.sql` now imports on XAMPP/MariaDB.** It was a MySQL 9 dump using the `utf8mb4_0900_ai_ci` collation, which MariaDB rejects (error 1273). It now uses `utf8mb4_unicode_ci`, creates the `online_exam_db` database itself, and keeps the 2 sample exams with 27 questions.
- **Personal data removed.** Test accounts (with email addresses and password hashes) and their attempts were removed; only the default admin `admin@example.com` / `password` remains.

### Docs
- README: default admin email corrected to `admin@example.com` (matches `database.sql`), table count corrected to 6, upload rules and the examiner role documented.
