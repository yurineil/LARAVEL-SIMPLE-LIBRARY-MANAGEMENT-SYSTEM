# Database setup (follows your .env)

Your `.env` uses:
- **DB_CONNECTION=mysql**
- **DB_DATABASE=book_system**
- **DB_USERNAME=root**
- **DB_PASSWORD=** (empty)

**Steps:**

1. Create the database (if it doesn’t exist):
   - In MySQL: `CREATE DATABASE book_system;`

2. Run migrations (creates `users`, `sessions`, `password_reset_tokens`, `cache`, `jobs`, `students`, `authors`, `books`, `author_book`, `borrows`, `borrow_book`, etc.):
   ```bash
   php artisan migrate
   ```

3. Seed admin + sample data (admin user so you can login):
   ```bash
   php artisan db:seed
   ```
   **Admin login:** email `admin@library.com` / password `password`

If you already ran migrations/seed before and login still fails, reset and reseed:
```bash
php artisan migrate:fresh --seed
```
Then use `admin@library.com` / `password` to log in.

**If existing users still can’t log in** (e.g. they were created before the password fix):
- **Option A:** They use **Forgot password** on the login page and set a new password.
- **Option B:** Reset the admin password once (run in project folder):
  ```bash
  php artisan tinker
  ```
  Then in tinker:
  ```php
  \App\Models\User::where('email', 'admin@library.com')->first()?->update(['password' => 'password']);
  exit
  ```
  After that, log in with `admin@library.com` / `password`.
