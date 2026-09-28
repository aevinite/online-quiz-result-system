# Online Quiz & Result Management System

A small **Web Technology (WT)** academic project that demonstrates all five
required technologies:

| Technology | Where it is used |
|-----------|------------------|
| **HTML**       | Page structure, forms, quiz interface |
| **CSS**        | `css/style.css` — design + responsive layout |
| **JavaScript** | Timer, one-question navigation, answer validation (`js/quiz.js`, `js/main.js`) |
| **PHP**        | Login/registration, quiz processing, result calculation, admin CRUD |
| **Database**   | PostgreSQL on Supabase — stores `users`, `questions`, `results` (and `admins`) |

---

## Features

- Student **registration & login** (passwords hashed with `password_hash`)
- **Timed quiz** (5 min) with a JavaScript countdown that auto-submits
- One-question-at-a-time UI with progress bar
- **Instant result** — score + percentage + pass/fail, saved to the database
- **Quiz history** for each student
- **Admin panel** to Add / Edit / Delete questions
- Responsive design (works on mobile)

---

## Requirements

- **PHP 8** with the `pdo_pgsql` extension enabled
- A **Supabase** project (free plan is enough)
- A web browser

---

## Setup (step by step)

1. **Create the tables:**
   - In Supabase open **SQL Editor**, paste `database.sql` and click **Run**.
   - This creates all tables with sample questions.

2. **Point the app at Supabase** (Project → **Connect** → **Session pooler**):
   - On Vercel, set `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME` as environment variables.
   - Locally, create `config/db.local.php` (ignored by git):
     ```php
     <?php
     $DB_LOCAL = ['host' => '...pooler.supabase.com', 'port' => 5432,
                  'user' => 'postgres.<project-ref>', 'pass' => '...', 'name' => 'postgres'];
     ```

3. **Run locally:**
   - `php -S localhost:8000` inside the project folder.

4. **Create the admin account (one time, locally):**
   - Visit <http://localhost:8000/setup.php>
   - This creates the default admin:
     - **Username:** `admin`
     - **Password:** `admin123`

5. **Open the app:**
   - <http://localhost:8000/index.php>

---

## Default Logins

| Role    | URL                                | Username / Email | Password |
|---------|------------------------------------|------------------|----------|
| Admin   | `admin/login.php`                  | `admin`          | `admin123` |
| Student | `register.php` (create your own)   | your email       | your password |

---

## Folder Structure

```
quiz-system/
├── config/db.php            # MySQL connection
├── includes/                # shared header & footer
├── css/style.css            # styles (responsive)
├── js/
│   ├── quiz.js              # timer + quiz navigation
│   └── main.js             # form validation
├── admin/                   # admin login + question CRUD
│   ├── login.php  logout.php  auth.php  admin_header.php
│   ├── dashboard.php
│   ├── add_question.php  edit_question.php  delete_question.php
├── index.php                # home
├── register.php  login.php  logout.php
├── quiz.php                 # quiz interface
├── submit_quiz.php          # PHP scoring
├── result.php               # result + history
├── setup.php                # one-time admin seeder
├── database.sql             # MySQL schema + sample data
└── README.md
```

---

## Database Tables

- **users** — `id, name, email, password, created_at`
- **questions** — `id, question, option_a..d, correct_option, created_at`
- **results** — `id, user_id, score, total_questions, percentage, taken_at`
- **admins** — `id, username, password`

---

## Notes

- The **pass mark** is 40% (change it in `result.php`).
- The **quiz timer** is 5 minutes (change `window.QUIZ_TIME` in `quiz.php`).
- Correct answers are checked **on the server** (in `submit_quiz.php`),
  so they are never exposed to the browser.
