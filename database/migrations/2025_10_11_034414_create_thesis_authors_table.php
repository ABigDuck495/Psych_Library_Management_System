<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('thesis_authors', function (Blueprint $table) {
            $table->foreignId('thesis_id')->constrained('theses')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('authors')->onDelete('cascade');
            $table->primary(['thesis_id', 'author_id']);
            
            // Performance indexes
            $table->index('author_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('thesis_authors');
    }
};