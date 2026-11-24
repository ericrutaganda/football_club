# Football Club Management System

A PHP and MySQL application for managing football club players, fixtures, and training sessions.

## Requirements

- PHP 8.0 or later with PDO MySQL enabled
- MySQL or MariaDB
- Apache (XAMPP is supported)

## Setup

1. Copy the complete project folder into the web root (for XAMPP, `C:\xampp\htdocs\football_club`).
2. Start Apache and MySQL.
3. Create a database named `football_club`.
4. Confirm the settings in `config/config.php` match your MySQL server:

   | Setting | Default |
   | --- | --- |
   | `DB_HOST` | `localhost` |
   | `DB_NAME` | `football_club` |
   | `DB_USER` | `root` |
   | `DB_PASS` | empty |

   These are local XAMPP defaults only. Use a dedicated database user and a strong password outside local development.
5. Import `schema.sql`, then import `seed.sql`, into the `football_club` database using phpMyAdmin or the MySQL command line:

   Run these from Command Prompt (or use phpMyAdmin's Import tab):

   ```cmd
   mysql -u root football_club < schema.sql
   mysql -u root football_club < seed.sql
   ```

   Add `-p` after `root` if your MySQL account has a password; the client will prompt for it.
6. Open `http://localhost/football_club/`.

Alternatively, open `http://localhost/football_club/install.php` to create the tables and initial administrator. The database must already exist. Delete or rename `install.php` after setup.

### Initial administrator

- Username: `Rutaganda Eric`
- Email: `rutagandaeric22@gmail.com`
- Password: `123456789`

The seed script inserts the initial account only if its unique username or email is not already present; it does not overwrite existing credentials. Change the password after sign-in. New passwords must be at least 12 characters and no more than 72 bytes.

## Source code

The complete source is included in this project:

- `index.php` — front controller and route table
- `app/controllers/`, `app/models/`, `app/core/`, and `app/views/` — application code
- `config/` and `helpers/` — configuration and shared helpers
- `public/` — CSS and JavaScript assets
- `schema.sql` and `seed.sql` — database schema and demo data

## Routes

| Route | Method | Purpose |
| --- | --- | --- |
| `index.php?route=login` | GET, POST | Sign in with username or email |
| `index.php?route=logout` | POST | Sign out |
| `index.php?route=account/password` | GET, POST | Change password |
| `index.php?route=dashboard` | GET | Dashboard and upcoming matches |
| `index.php?route=players` | GET | Player directory |
| `index.php?route=players/form` | GET | Add or edit a player |
| `index.php?route=players/save` | POST | Create or update a player |
| `index.php?route=players/delete` | POST | Delete a player |
| `index.php?route=matches` | GET | Match schedule and results |
| `index.php?route=matches/form` | GET | Schedule or edit a match |
| `index.php?route=matches/save` | POST | Create or update a match |
| `index.php?route=matches/delete` | POST | Delete a match |
| `index.php?route=trainings` | GET | Training schedule |
| `index.php?route=trainings/form` | GET | Create or edit a session |
| `index.php?route=trainings/save` | POST | Create or update a session |
| `index.php?route=trainings/delete` | POST | Delete a session |
| `index.php?route=api/summary` | GET | Authenticated JSON counts |

All write operations require a valid CSRF token. The API response contains `players`, `matches`, and `trainings` counts.

## Test evidence

Checks run locally against PHP 8.0.28 and the XAMPP application on 2026-11-19:

- PHP syntax check passed for all project PHP files.
- Sign-in succeeded with both the administrator username and email.
- Dashboard, player, match, training, and password pages rendered successfully.
- Authenticated API summary returned valid JSON with all three counts.
- GET logout was rejected with HTTP 405 and left the session signed in; POST logout succeeded.
- An invalid route returned HTTP 404.
- Missing CSRF tokens were rejected; valid tokens were accepted.
- Date/time, score, and record-ID validation helper checks passed.
- Wrong current password was rejected by the password-change flow.
- `schema.sql` and `seed.sql` executed successfully in a disposable validation database; rerunning the seed produced no duplicate sample records, and the seeded password hash verified.

See [TEST_PLAN.md](TEST_PLAN.md) for the manual test cases, expected outcomes, and execution status. CRUD write operations and a successful password change are listed for manual verification; they were not run as part of the checks above.

## 2–3 minute live demo

Start Apache and MySQL, sign in with the initial account, and use this short walkthrough:

1. **0:00–0:25** — Sign in with the username or email and show the dashboard summary.
2. **0:25–0:55** — Open Players and show the directory and add-player form.
3. **0:55–1:25** — Open Matches and show the schedule, scores, statuses, and match form.
4. **1:25–1:55** — Open Training and show the schedule and session form.
5. **1:55–2:20** — Open Change password and show the current/new password checks without submitting a change during the demo.
6. **2:20–2:40** — Show the authenticated JSON summary route, then sign out.

Set `APP_DEBUG` to `true` only for local troubleshooting; it defaults to `false`.
