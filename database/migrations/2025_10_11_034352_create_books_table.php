<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('SKU')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->year('year_published');
            //$table->string('image_path')->nullable(); // Stores the image file path, nullable if no image
            $table->foreignId('category_id')->constrained('categories');
            $table->timestamps();

            // Performance indexes
            $table->index('title');
            $table->index('year_published');
            $table->index(['category_id', 'year_published']);
            $table->index('created_at');
            $table->fullText(['title', 'description']); // For search functionality
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
};