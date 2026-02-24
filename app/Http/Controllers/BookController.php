<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /** Category options: clean labels, Fiction and Nonfiction. */
    public static function categoryOptions(): array
    {
        return [
            'Fiction' => [
                'Fantasy', 'Sci-Fi', 'Romance', 'Mystery/Thriller', 'Horror',
                'Historical Fiction', 'Literary Fiction', 'Young Adult', 'Graphic Novels/Manga',
                'Dystopian', 'Magical Realism',
            ],
            'Nonfiction' => [
                'Biography/Memoir', 'Self-Help', 'True Crime', 'History & Geography',
                'Business & Economics', 'Science & Technology', 'Religion & Spirituality',
                'Cookbooks & Hobbies',
            ],
        ];
    }

    public function index(Request $request)
    {
        $books = Book::with('authors')
            ->withCount(['borrowItems as borrowed_count' => fn ($q) => $q->whereNull('returned_at')])
            ->when($request->q, function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('category', 'like', '%' . $request->q . '%');
            })
            ->orderBy('title')
            ->paginate(10)
            ->withQueryString();

        return view('books.index', compact('books'));
    }

    public function show(Book $book)
    {
        $book->load('authors');
        return view('books.show', compact('book'));
    }

    public function create()
    {
        $authors = Author::orderBy('name')->get();
        $categoryOptions = static::categoryOptions();
        return view('books.create', compact('authors', 'categoryOptions'));
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'copies' => 'required|integer|min:1',
            'author_ids' => 'nullable|array',
            'author_ids.*' => 'exists:authors,id',
            'author_new' => 'nullable|string|max:255',
        ];
        if ($request->input('category') === '__other__') {
            $rules['category_other'] = 'required|string|max:255';
        }
        $validated = $request->validate($rules);

        $category = $validated['category'] === '__other__'
            ? $validated['category_other']
            : $validated['category'];

        $book = Book::create([
            'title' => $validated['title'],
            'category' => $category,
            'description' => $validated['description'] ?? null,
            'copies' => $validated['copies'],
        ]);

        $authorIds = array_values(array_filter(array_map('intval', $validated['author_ids'] ?? [])));
        $newName = trim($validated['author_new'] ?? '');
        if ($newName !== '') {
            $author = Author::firstOrCreate(['name' => $newName]);
            $authorIds[] = $author->id;
        }
        if (!empty($authorIds)) {
            $book->authors()->sync(array_unique($authorIds));
        }

        return redirect()->route('books.index')->with('success', 'Book added successfully.');
    }

    public function edit(Book $book)
    {
        $book->load('authors');
        $authors = Author::orderBy('name')->get();
        $categoryOptions = static::categoryOptions();
        return view('books.edit', compact('book', 'authors', 'categoryOptions'));
    }

    public function update(Request $request, Book $book)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'copies' => 'required|integer|min:0',
            'author_ids' => 'nullable|array',
            'author_ids.*' => 'exists:authors,id',
            'author_new' => 'nullable|string|max:255',
        ];
        if ($request->input('category') === '__other__') {
            $rules['category_other'] = 'required|string|max:255';
        }
        $validated = $request->validate($rules);

        $category = $validated['category'] === '__other__'
            ? $validated['category_other']
            : $validated['category'];

        $book->update([
            'title' => $validated['title'],
            'category' => $category,
            'description' => $validated['description'] ?? null,
            'copies' => $validated['copies'],
        ]);

        $authorIds = array_values(array_filter(array_map('intval', $validated['author_ids'] ?? [])));
        $newName = trim($validated['author_new'] ?? '');
        if ($newName !== '') {
            $author = Author::firstOrCreate(['name' => $newName]);
            $authorIds[] = $author->id;
        }
        $book->authors()->sync(array_unique($authorIds));

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        if ($book->borrowedCount() > 0) {
            return back()->with('error', 'Cannot delete book with copies still borrowed.');
        }
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted.');
    }
}
