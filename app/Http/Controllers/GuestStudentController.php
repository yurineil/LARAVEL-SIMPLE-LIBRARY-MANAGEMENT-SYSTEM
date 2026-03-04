<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\BorrowBook;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuestStudentController extends Controller
{
    protected const SESSION_KEY = 'guest_student_id';

    protected function getGuestStudent(): ?Student
    {
        $id = session(self::SESSION_KEY);
        return $id ? Student::find($id) : null;
    }

    protected function requireGuestStudent()
    {
        $student = $this->getGuestStudent();
        if (! $student) {
            return redirect()->route('guest.student.portal')
                ->with('error', 'Please enter your Student ID first.');
        }
        return $student;
    }

    /** Show form to enter Student ID (no login). */
    public function portal()
    {
        return view('guest.student-portal');
    }

    /** Store Student ID in session and redirect to borrows. */
    public function identify(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string|max:50',
        ]);

        $student = Student::where('student_id', trim($request->student_id))->first();
        if (! $student) {
            return back()->withErrors(['student_id' => 'Student ID not found.'])->withInput();
        }

        session([self::SESSION_KEY => $student->id]);
        return redirect()->route('guest.borrows')->with('success', 'Welcome, ' . $student->name . '. You can borrow or return books.');
    }

    /** List borrows for guest student. */
    public function borrows(Request $request)
    {
        $student = $this->requireGuestStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        $borrows = Borrow::with(['borrowItems.book'])
            ->withCount('outstandingItems')
            ->where('student_id', $student->id)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('guest.borrows', compact('borrows', 'student'));
    }

    /** Show borrow form (select books + due date). */
    public function borrowCreate()
    {
        $student = $this->requireGuestStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        $books = Book::with('authors')
            ->withCount(['borrowItems as borrowed_count' => fn ($q) => $q->whereNull('returned_at')])
            ->orderBy('title')
            ->get()
            ->filter(fn ($b) => $b->availableCopies() >= 1);

        return view('guest.borrow-create', compact('books', 'student'));
    }

    /** Store new borrow for guest student. */
    public function borrowStore(Request $request)
    {
        $student = $this->requireGuestStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        $validated = $request->validate([
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
            'student_id' => $student->id,
            'borrow_date' => now()->toDateString(),
            'due_date' => $validated['due_date'],
        ]);
        foreach ($validated['book_ids'] as $bookId) {
            $borrow->borrowItems()->create(['book_id' => $bookId]);
        }

        return redirect()->route('guest.borrows')->with('success', 'Borrow record created successfully.');
    }

    /** Show return form for one borrow. */
    public function returnForm(Borrow $borrow)
    {
        $student = $this->requireGuestStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        if ($borrow->student_id !== $student->id) {
            abort(403, 'You can only return your own borrows.');
        }

        $borrow->load(['student', 'borrowItems.book']);
        $outstanding = $borrow->borrowItems()->whereNull('returned_at')->with('book')->get();
        if ($outstanding->isEmpty()) {
            return redirect()->route('guest.borrows')->with('info', 'All books in this borrow are already returned.');
        }

        return view('guest.return', compact('borrow', 'outstanding', 'student'));
    }

    /** Process return and show fine. */
    public function processReturn(Request $request)
    {
        $student = $this->requireGuestStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        $validated = $request->validate([
            'borrow_id' => 'required|exists:borrows,id',
            'return_date' => 'required|date',
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'exists:borrow_book,id',
        ]);

        $borrow = Borrow::findOrFail($validated['borrow_id']);
        if ($borrow->student_id !== $student->id) {
            abort(403, 'You can only return your own borrows.');
        }

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

        return redirect()->route('guest.borrows')->with('success', $message);
    }

    /** Clear guest session and return to portal. */
    public function endSession(Request $request)
    {
        $request->session()->forget(self::SESSION_KEY);
        return redirect()->route('guest.student.portal')->with('success', 'Session ended. Enter your Student ID again to continue.');
    }
}
