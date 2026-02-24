<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'description',
        'copies',
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'author_book')
            ->withTimestamps();
    }

    public function borrowItems()
    {
        return $this->hasMany(BorrowBook::class, 'book_id');
    }

    /** Number of copies currently out (not yet returned). Uses borrowed_count when loaded via withCount. */
    public function borrowedCount(): int
    {
        return (int) ($this->attributes['borrowed_count'] ?? BorrowBook::where('book_id', $this->id)
            ->whereNull('returned_at')
            ->count());
    }

    public function availableCopies(): int
    {
        return max(0, $this->copies - $this->borrowedCount());
    }
}
