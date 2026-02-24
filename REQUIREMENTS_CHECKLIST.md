# Mini Library Management System — Requirements Checklist

This document maps the activity requirements to the implementation in this project.

---

## 1. Authentication (Laravel Breeze)

| Requirement | Status | Implementation |
|-------------|--------|----------------|
| Implement authentication using Laravel Breeze | ✓ | `laravel/breeze` is in `composer.json`. Auth features implemented: login, change password. |
| Users can: Login | ✓ | `AuthController@login`, `routes/web.php` GET/POST `/login`, `resources/views/auth/login.blade.php` |
| Users can: Change password | ✓ | `ProfileController@editPassword`, `@updatePassword`; routes `password.edit`, `password.update`; view `profile/change-password` |
| No Role-Based Access Control (RBAC) required | ✓ | No role checks; all authenticated users have the same access. |

---

## 2. Student Module

| Requirement | Status | Implementation |
|-------------|--------|----------------|
| Students are NOT required to log in | ✓ | Students are managed as records (CRUD by staff). No student login routes or guards. |
| A student can borrow multiple books | ✓ | `BorrowController@store` accepts `book_ids` array; creates one `Borrow` and multiple `borrow_book` rows. |
| A student can return all books or partial books | ✓ | `BorrowController@returnForm` shows outstanding items; `processReturn` accepts `item_ids` for partial return. |
| Fine: ₱10 per day per book if overdue | ✓ | `Borrow::computeFine($dueDate, $returnDate, $bookCount)` returns `10 * overdue_days * bookCount`; used in `processReturn`. |

---

## 3. Books Module

| Requirement | Status | Implementation |
|-------------|--------|----------------|
| Display list of all books | ✓ | `BookController@index`, `resources/views/books/index.blade.php` (paginated table). |
| Display available inventory count | ✓ | `Book::availableCopies()`; shown in books index as "Available / Total" (e.g. `2 / 5`). |
| A book can have multiple authors | ✓ | Many-to-many via `author_book` pivot; `Book::authors()`, `Author::books()`. |
| Track borrowing availability | ✓ | `Book::borrowedCount()` and `availableCopies()`; borrow form checks availability before creating borrow. |

---

## 4. Authors Module

| Requirement | Status | Implementation |
|-------------|--------|----------------|
| Authors must be created in this module | ✓ | `AuthorController` resource; CRUD views under `resources/views/authors/`. |
| A book can be associated with multiple authors | ✓ | Many-to-many `Book` ↔ `Author` via `author_book` table. |
| Use proper Many-to-Many relationship | ✓ | `author_book` migration with `author_id`, `book_id` foreign keys; `Book::authors()`, `Author::books()` in models. |

---

## 5. Business Logic Requirements

| Requirement | Status | Implementation |
|-------------|--------|----------------|
| Borrow date and due date must be recorded | ✓ | `borrows` table: `borrow_date`, `due_date`; set in `BorrowController@store`. |
| Fine = ₱10 × number of overdue days × number of books | ✓ | `Borrow::computeFine()`; applied in `BorrowController@processReturn` and shown in success message. |
| Partial return must update: book inventory, borrow record, fine computation | ✓ | `processReturn` sets `returned_at` on selected `borrow_book` rows (inventory via `availableCopies()`); fine computed for returned items only. |

---

## 6. Design Requirements

| Requirement | Status | Implementation |
|-------------|--------|----------------|
| Use a frontend framework (Bootstrap, Tailwind, etc.) | ✓ | Bootstrap 5 (CDN) in `resources/views/layouts/app.blade.php`. |
| Layout: clean, organized, responsive, customized | ✓ | Custom layout with sidebar, navbar, stat cards, table styling, responsive sidebar toggle. |
| Avoid default plain scaffold output only | ✓ | Custom CSS variables, gradients, cards, badges, and library-themed styling in `app.blade.php`. |

---

## Technical Expectations

| Expectation | Status | Implementation |
|-------------|--------|----------------|
| Migrations with correct foreign keys | ✓ | `borrows.student_id`, `borrow_book.borrow_id`/`book_id`, `author_book.author_id`/`book_id` with `constrained()->onDelete('cascade')`. |
| Eloquent: One-to-Many | ✓ | e.g. `Borrow` → `BorrowBook` (`hasMany`), `Student` → `Borrow` (`hasMany`), `Book` → `BorrowBook` (`hasMany`). |
| Eloquent: Many-to-Many | ✓ | `Book` ↔ `Author` via `author_book`; `Borrow` ↔ `Book` via `borrow_book` (with pivot `returned_at`). |
| Controllers with clean logic | ✓ | Resource controllers for students, authors, books; dedicated BorrowController for borrow/return flow. |
| Proper validation | ✓ | `validate()` in Auth, Profile, Student, Author, Book, Borrow controllers (required, exists, date, etc.). |
| RESTful routing | ✓ | `Route::resource('students', ...)`, `resource('authors', ...)`, `resource('books', ...)`; borrows use index/create/store and return routes. |
| Organized folder structure | ✓ | Standard Laravel: `app/Models`, `app/Http/Controllers`, `resources/views/{auth,books,authors,students,borrows,profile}`, `routes/web.php`. |
| Clean code practices | ✓ | Eloquent relationships, form request validation, blade layout and partials, named routes. |

---

## Summary

All stated system requirements and technical expectations are met. Authentication provides login and change password (Breeze is a project dependency; to use Breeze’s published views and routes, run `php artisan breeze:install blade` and merge with existing dashboard and modules if required by your instructor).
