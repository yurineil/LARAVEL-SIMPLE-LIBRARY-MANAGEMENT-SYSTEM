# Mini Library Management System — Assignment Implementation

This project implements the required **Mini Library Management System** using Laravel MVC with the following features.

## Requirements Checklist

### 1. Authentication (Laravel Breeze–compatible)
- **Login** — Implemented via `AuthController` (can be replaced by Breeze after `php artisan breeze:install blade`).
- **Change password** — Profile > Change Password (`/profile/password`).
- **No RBAC** — No role-based access control; all logged-in users have the same access.

**Optional:** To use Laravel Breeze scaffolding, run:
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
```
Then merge Breeze routes/views with existing auth as needed.

### 2. Student Module
- **Students do NOT log in.** They are managed as records (name, email, student ID).
- **Borrow:** Staff records a borrow by selecting a **student** and **books** and setting a **due date**.
- **Return:** Full or **partial return**; staff selects which books are being returned.
- **Fine:** **₱10 per day per book** if overdue. Formula: `Fine = ₱10 × overdue days × number of books (in that return)`.

### 3. Books Module
- **List of all books** with title, authors, category, **available inventory** (available / total copies).
- **A book can have multiple authors** (many-to-many via `author_book` pivot).
- **Borrowing availability** is tracked: available = copies − currently borrowed count.

### 4. Authors Module
- **Authors are created** in this module (CRUD).
- **Many-to-many** with books: one book can have many authors, one author can have many books.

### 5. Business Logic
- **Borrow date** and **due date** are stored per borrow transaction.
- **Fine** = ₱10 × number of overdue days × number of books (for the books returned in that transaction).
- **Partial return** updates: book inventory (via `returned_at` on pivot), borrow record, and fine is computed at return time.

### 6. Design
- **Frontend:** Bootstrap 5 (CDN) with custom layout.
- **Layout:** Clean, organized, responsive, customized for a library system.

## Technical Implementation

- **Migrations:** Foreign keys and tables for `students`, `authors`, `author_book`, `books` (no single `author` column), `borrows` (student_id, borrow_date, due_date), `borrow_book` (borrow_id, book_id, returned_at).
- **Eloquent:** One-to-Many (e.g. Student → Borrows), Many-to-Many (Book ↔ Author, Borrow ↔ Book via pivot).
- **Controllers:** RESTful resource controllers for Students, Authors, Books; BorrowController for index, create, store, return form, process return.
- **Validation:** Request validation in all store/update/process methods.
- **Routes:** RESTful; auth-protected for dashboard, students, authors, books, borrows, profile.

## How to Run

1. **Fresh migration and seed** (required after schema changes):
   ```bash
   php artisan migrate:fresh --seed
   ```
2. **Login:** `admin@library.com` / `password`
3. **Menu:** Dashboard, Students, Authors, Books, Borrows, Change Password.

## Fine Computation

- When processing a return, the system uses **return date** and **due date**.
- **Overdue days** = max(0, return date − due date).
- **Fine** = 10 × overdue days × (number of books selected in that return).
- Displayed in the success message after return (e.g. “2 book(s) returned. Fine due: ₱100.00”).
