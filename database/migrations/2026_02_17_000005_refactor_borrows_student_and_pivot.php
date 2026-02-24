<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('borrows');

        Schema::create('borrows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->date('borrow_date');
            $table->date('due_date');
            $table->timestamps();
        });

        Schema::create('borrow_book', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrow_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();

            $table->unique(['borrow_id', 'book_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrow_book');
        Schema::dropIfExists('borrows');

        Schema::create('borrows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->date('borrow_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['borrowed', 'returned'])->default('borrowed');
            $table->timestamps();
        });
    }
};
