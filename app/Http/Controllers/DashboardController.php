<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalStudents = Student::count();
        $totalAuthors = Author::count();
        $activeBorrows = Borrow::whereHas('outstandingItems')->count();
        $totalBorrowedCopies = \App\Models\BorrowBook::whereNull('returned_at')->count();

        return view('dashboard', compact(
            'totalBooks',
            'totalStudents',
            'totalAuthors',
            'activeBorrows',
            'totalBorrowedCopies'
        ));
    }
}
