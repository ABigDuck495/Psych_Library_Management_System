<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('book_copies', function (Blueprint $table) {
            $table->id('copy_id');
            $table->foreignId('book_id')
                ->constrained('books')
                ->onDelete('cascade'); //makes it so it deletes the copies when you delete a book
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            // Performance indexes
            $table->index('book_id');
            $table->index('is_available');
            $table->index(['book_id', 'is_available']); // For finding available copies of a book
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_copies');
    }
};