<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\BorrowBook;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        $borrows = Borrow::with(['student', 'borrowItems.book'])
            ->withCount('outstandingItems')
            ->when($request->student_id, fn ($q) => $q->where('student_id', $request->student_id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('borrows.index', compact('borrows'));
    }

    public function create()
    {
        $students = Student::orderBy('name')->get();
        $books = Book::with('authors')
            ->withCount(['borrowItems as borrowed_count' => fn ($q) => $q->whereNull('returned_at')])
            ->orderBy('title')
            ->get();
        return view('borrows.create', compact('students', 'books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'due_date' => 'required|date|after_or_equal:today',
            'book_ids' => 'required|array|min:1',
            'book_ids.*' => 'exists:books,id',
        ]);

        $books = Book::whereIn('id', $validated['book_ids'])->get();
        foreach ($books as $book) {
            if ($book->availableCopies() < 1) {
                return back()->with('error', "Not enough copies available for: {$book->title}.")->withInput();
            }
        }

        $borrow = Borrow::create([
            'student_id' => $validated['student_id'],
            'borrow_date' => now()->toDateString(),
            'due_date' => $validated['due_date'],
        ]);

        foreach ($validated['book_ids'] as $bookId) {
            $borrow->borrowItems()->create(['book_id' => $bookId]);
        }

        return redirect()->route('borrows.index')->with('success', 'Borrow record created successfully.');
    }

    public function returnForm(Borrow $borrow)
    {
        $borrow->load(['student', 'borrowItems.book']);
        $outstanding = $borrow->borrowItems()->whereNull('returned_at')->with('book')->get();
        return view('borrows.return', compact('borrow', 'outstanding'));
    }

    public function processReturn(Request $request)
    {
        $validated = $request->validate([
            'borrow_id' => 'required|exists:borrows,id',
            'return_date' => 'required|date',
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'exists:borrow_book,id',
        ]);

        $borrow = Borrow::findOrFail($validated['borrow_id']);
        $returnDate = Carbon::parse($validated['return_date'])->startOfDay();
        $dueDate = $borrow->due_date;

        $items = BorrowBook::where('borrow_id', $borrow->id)
            ->whereIn('id', $validated['item_ids'])
            ->whereNull('returned_at')
            ->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'No valid items to return.');
        }

        $fine = Borrow::computeFine($dueDate, $returnDate, $items->count());

        foreach ($items as $item) {
            $item->update(['returned_at' => $returnDate]);
        }

        $message = $items->count() . ' book(s) returned.';
        if ($fine > 0) {
            $message .= ' Fine due: ₱' . number_format($fine, 2);
        }

        return redirect()->route('borrows.index')->with('success', $message);
    }
}
