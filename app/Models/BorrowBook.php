<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowBook extends Model
{
    protected $table = 'borrow_book';

    protected $fillable = ['borrow_id', 'book_id', 'returned_at'];

    protected function casts(): array
    {
        return [
            'returned_at' => 'datetime',
        ];
    }

    public function borrow()
    {
        return $this->belongsTo(Borrow::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
