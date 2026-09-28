-- =========================================================
--  Online Quiz & Result Management System
--  PostgreSQL Database Schema (Supabase)
--  Run this file once in the Supabase SQL Editor.
-- =========================================================

-- ---------------------------------------------------------
--  Table: users  (students who register and take the quiz)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  id          SERIAL PRIMARY KEY,
  name        VARCHAR(100)  NOT NULL,
  email       VARCHAR(150)  NOT NULL UNIQUE,
  password    VARCHAR(255)  NOT NULL,          -- stored hashed (password_hash)
  created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------
--  Table: questions  (managed by admin)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS questions (
  id            SERIAL PRIMARY KEY,
  question      TEXT          NOT NULL,
  option_a      VARCHAR(255)  NOT NULL,
  option_b      VARCHAR(255)  NOT NULL,
  option_c      VARCHAR(255)  NOT NULL,
  option_d      VARCHAR(255)  NOT NULL,
  correct_option CHAR(1)      NOT NULL,         -- one of: A, B, C, D
  created_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------
--  Table: results  (one row per attempt)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS results (
  id             SERIAL PRIMARY KEY,
  user_id        INT           NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  score          INT           NOT NULL,
  total_questions INT          NOT NULL,
  percentage     DECIMAL(5,2)  NOT NULL,
  taken_at       TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------
--  Table: admins
--  Default login ->  username: admin   password: admin123
--  The admin row is created by visiting  setup.php  once
--  (so the password hash is generated correctly by PHP).
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
  id        SERIAL PRIMARY KEY,
  username  VARCHAR(50)  NOT NULL UNIQUE,
  password  VARCHAR(255) NOT NULL
);

-- ---------------------------------------------------------
--  Table: sessions  (login sessions, used when hosted on Vercel)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS sessions (
  id          VARCHAR(128) NOT NULL PRIMARY KEY,
  data        TEXT         NOT NULL,
  updated_at  INT          NOT NULL
);

-- The app connects directly as the database owner; lock these tables
-- away from Supabase's public REST API.
ALTER TABLE users     ENABLE ROW LEVEL SECURITY;
ALTER TABLE questions ENABLE ROW LEVEL SECURITY;
ALTER TABLE results   ENABLE ROW LEVEL SECURITY;
ALTER TABLE admins    ENABLE ROW LEVEL SECURITY;
ALTER TABLE sessions  ENABLE ROW LEVEL SECURITY;

-- ---------------------------------------------------------
--  Sample questions so the quiz works right away
-- ---------------------------------------------------------
INSERT INTO questions (question, option_a, option_b, option_c, option_d, correct_option) VALUES
('Which language is used to structure the content of a web page?', 'CSS', 'HTML', 'PHP', 'MySQL', 'B'),
('Which language runs in the browser to make pages interactive?', 'PHP', 'MySQL', 'JavaScript', 'SQL', 'C'),
('Which of these is a server-side scripting language?', 'HTML', 'CSS', 'JavaScript', 'PHP', 'D'),
('Which technology is used to store data in this project?', 'MySQL', 'HTML', 'CSS', 'JavaScript', 'A'),
('Which CSS property changes the text color?', 'font-size', 'color', 'background', 'margin', 'B'),
('What does the SQL keyword SELECT do?', 'Delete data', 'Insert data', 'Retrieve data', 'Update data', 'C'),
('Which tag creates a hyperlink in HTML?', '<link>', '<a>', '<href>', '<url>', 'B'),
('Which PHP function safely hashes a password?', 'md5()', 'hash()', 'password_hash()', 'encrypt()', 'C');
