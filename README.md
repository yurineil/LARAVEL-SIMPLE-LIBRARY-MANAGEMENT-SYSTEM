# Library MS

A simple library management system. Students can browse books, borrow and return them. Admins manage books, students, borrows, and users.

## About the system

Library MS is a web app for managing a small library. It has two roles:

- **Students** – Log in, browse books (with search and category filter), add books to a borrow list, set a due date and submit. They can return books (full or partial) from “My Borrows” and change their password.
- **Admins** – Log in after approval, manage students, authors, and books (add/edit/delete). They record borrows and process returns. They can approve new admin accounts and change password.

Books have titles, categories, copies, and can have multiple authors. Borrowing tracks due dates; late returns get a fine (₱10 per overdue day per book). The app uses Laravel (PHP), Bootstrap for the interface, and a MySQL database.

## How to run

1. Copy `.env.example` to `.env` and set your database name, username, and password.
2. Run: `php artisan migrate`
3. Run: `php artisan serve`
4. Open http://localhost:8000 in your browser.

## What you can do

- **Login / Register** – Register as Admin or Student. New admins need approval from an existing admin.
- **Students** – Browse books, add books to a list, submit a borrow with a due date, return books, change password.
- **Admins** – Manage students, authors, books, and borrows; process returns; approve new admins; change password.

## Fine

If a book is returned after the due date: **Fine = ₱10 × overdue days × number of books.**

## Team

- Programmer: Yuri Neil Bayron  
- Members: Nhevie Mae Bentillo, Trexie Mae Capeña, Ailyn Cabañas  

Built with Laravel and Bootstrap.
