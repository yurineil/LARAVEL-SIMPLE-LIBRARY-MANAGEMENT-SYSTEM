<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'borrow_date',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',
            'due_date' => 'date',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'borrow_book')
            ->withPivot('returned_at')
            ->withTimestamps();
    }

    public function borrowItems()
    {
        return $this->hasMany(BorrowBook::class);
    }

    /** Books not yet returned in this borrow */
    public function outstandingItems()
    {
        return $this->borrowItems()->whereNull('returned_at');
    }

    /** Whether this borrow has any items not yet returned. Uses outstanding_items_count when loaded via withCount. */
    public function hasOutstanding(): bool
    {
        $count = $this->attributes['outstanding_items_count'] ?? null;
        if ($count !== null) {
            return (int) $count > 0;
        }
        return $this->outstandingItems()->exists();
    }

    /** Compute fine for a set of items returned on a given date. Fine = ₱10 × overdue days × number of books. */
    public static function computeFine($dueDate, $returnDate, int $bookCount): float
    {
        $due = \Carbon\Carbon::parse($dueDate)->startOfDay();
        $return = \Carbon\Carbon::parse($returnDate)->startOfDay();
        $daysOverdue = $return->gt($due) ? $due->diffInDays($return) : 0;
        return 10.0 * $daysOverdue * $bookCount;
    }

    /** Number of days overdue (0 if not overdue or no outstanding items). Uses outstanding_items_count when loaded via withCount. */
    public function overdueDays(): int
    {
        $count = (int) ($this->attributes['outstanding_items_count'] ?? $this->outstandingItems()->count());
        if ($count === 0) {
            return 0;
        }
        $due = \Carbon\Carbon::parse($this->due_date)->startOfDay();
        $today = \Carbon\Carbon::today();
        return $today->gt($due) ? (int) $due->diffInDays($today) : 0;
    }

    /** Potential fine if outstanding items were returned today. Fine = ₱10 × overdue days × number of books. Uses outstanding_items_count when loaded via withCount. */
    public function currentFine(): float
    {
        $count = (int) ($this->attributes['outstanding_items_count'] ?? $this->outstandingItems()->count());
        if ($count === 0) {
            return 0.0;
        }
        return self::computeFine($this->due_date, now()->toDateString(), $count);
    }
}
