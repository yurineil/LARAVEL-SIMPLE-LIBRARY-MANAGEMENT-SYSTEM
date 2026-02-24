<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrow_book', function (Blueprint $table) {
            $table->index(['book_id', 'returned_at']);
            $table->index(['borrow_id', 'returned_at']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::table('borrow_book', function (Blueprint $table) {
            $table->dropIndex(['book_id', 'returned_at']);
            $table->dropIndex(['borrow_id', 'returned_at']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['category']);
        });
    }
};
