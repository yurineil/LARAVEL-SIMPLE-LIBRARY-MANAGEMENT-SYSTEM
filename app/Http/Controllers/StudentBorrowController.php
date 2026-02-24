<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\BorrowBook;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentBorrowController extends Controller
{
    /** Get or create the Student record for the current logged-in user (by email). */
    protected function getStudentForCurrentUser(): Student
    {
        $user = Auth::user();
        return Student::firstOrCreate(
            ['email' => $user->email],
            ['name' => $user->name, 'student_id' => 'U' . $user->id]
        );
    }

    /** Only student role can access; redirect admin to dashboard. Returns redirect or null. */
    protected function ensureStudent()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            return redirect()->route('dashboard');
        }
        return null;
    }

    public function index(Request $request)
    {
        if ($r = $this->ensureStudent()) {
            return $r;
        }
        $query = Book::with('authors');
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('q')) {
            $term = '%' . $request->q . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('category', 'like', $term)
                    ->orWhereHas('authors', function ($q) use ($term) {
                        $q->where('name', 'like', $term);
                    });
            });
        }
        $books = $query
            ->withCount(['borrowItems as borrowed_count' => fn ($q) => $q->whereNull('returned_at')])
            ->orderBy('title')
            ->get();
        $categories = Book::distinct()->orderBy('category')->pluck('category');
        return view('student.books', compact('books', 'categories'));
    }

    /** Session key for the borrow list (array of book ids). */
    protected static function cartKey(): string
    {
        return 'borrow_cart';
    }

    public function addToCart(Book $book)
    {
        if ($r = $this->ensureStudent()) {
            return $r;
        }
        if ($book->availableCopies() < 1) {
            return redirect()->route('student.books')->with('error', "No copies available for: {$book->title}.");
        }
        $cart = session(self::cartKey(), []);
        $cart = array_unique(array_merge($cart, [$book->id]));
        session([self::cartKey() => array_values($cart)]);
        return redirect()->route('student.borrow.create')->with('success', "Added \"{$book->title}\" to your list. Set due date and submit when ready.");
    }

    public function removeFromCart(Book $book)
    {
        if ($r = $this->ensureStudent()) {
            return $r;
        }
        $cart = session(self::cartKey(), []);
        $cart = array_values(array_filter($cart, fn ($id) => (int) $id !== (int) $book->id));
        session([self::cartKey() => $cart]);
        return redirect()->route('student.borrow.create');
    }

    public function create(Request $request)
    {
        if ($r = $this->ensureStudent()) {
            return $r;
        }
        $cart = session(self::cartKey(), []);
        if ($request->has('book')) {
            $bookId = (int) $request->book;
            $book = Book::find($bookId);
            if ($book && $book->availableCopies() >= 1) {
                $cart = array_unique(array_merge($cart, [$bookId]));
                session([self::cartKey() => array_values($cart)]);
            }
            return redirect()->route('student.borrow.create');
        }
        $cartBooks = Book::whereIn('id', $cart)
            ->with('authors')
            ->withCount(['borrowItems as borrowed_count' => fn ($q) => $q->whereNull('returned_at')])
            ->orderBy('title')
            ->get()
            ->filter(fn ($b) => $b->availableCopies() >= 1);
        $validIds = $cartBooks->pluck('id')->all();
        if (count($validIds) !== count($cart)) {
            session([self::cartKey() => $validIds]);
        }
        return view('student.borrow-create', compact('cartBooks'));
    }

    public function store(Request $request)
    {
        if ($r = $this->ensureStudent()) {
            return $r;
        }
        $validated = $request->validate([
            'due_date' => 'required|date|after_or_equal:today',
            'book_ids' => 'required|array|min:1',
            'book_ids.*' => 'exists:books,id',
        ]);

        $student = $this->getStudentForCurrentUser();
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

        session()->forget(self::cartKey());
        return redirect()->route('student.my-borrows')->with('success', 'Borrow record created successfully.');
    }

    public function myBorrows(Request $request)
    {
        if ($r = $this->ensureStudent()) {
            return $r;
        }
        $student = $this->getStudentForCurrentUser();
        $borrows = Borrow::with(['borrowItems.book'])
            ->withCount('outstandingItems')
            ->where('student_id', $student->id)
            ->latest()
            ->paginate(10)
            ->withQueryString();
        return view('student.my-borrows', compact('borrows'));
    }

    /** Show return form for one of the student's borrows. */
    public function returnForm(Borrow $borrow)
    {
        if ($r = $this->ensureStudent()) {
            return $r;
        }
        $student = $this->getStudentForCurrentUser();
        if ($borrow->student_id !== $student->id) {
            abort(403, 'You can only return your own borrows.');
        }
        $borrow->load(['student', 'borrowItems.book']);
        $outstanding = $borrow->borrowItems()->whereNull('returned_at')->with('book')->get();
        if ($outstanding->isEmpty()) {
            return redirect()->route('student.my-borrows')->with('info', 'All books in this borrow are already returned.');
        }
        return view('student.return', compact('borrow', 'outstanding'));
    }

    /** Process return: record returned books and show any fine. */
    public function processReturn(Request $request)
    {
        if ($r = $this->ensureStudent()) {
            return $r;
        }
        $validated = $request->validate([
            'borrow_id' => 'required|exists:borrows,id',
            'return_date' => 'required|date',
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'exists:borrow_book,id',
        ]);

        $borrow = Borrow::findOrFail($validated['borrow_id']);
        $student = $this->getStudentForCurrentUser();
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

        return redirect()->route('student.my-borrows')->with('success', $message);
    }
}
