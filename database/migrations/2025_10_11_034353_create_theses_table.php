<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('theses', function (Blueprint $table) {
            $table->id();
            //$table->string('SKU')->unique();
            $table->enum('department', ['AB Psychology', 'BS Psychology']);
            $table->string('title');
            $table->text('abstract')->nullable();
            $table->year('year_published');
            //$table->string('advisor')->nullable();
            //$table->integer('pages')->nullable();
            $table->timestamps();

            // Performance indexes
            $table->index('title');
            $table->index('department');
            $table->index('year_published');
            $table->fullText(['title', 'abstract']); // For search functionality
        });
    }

    public function down()
    {
        Schema::dropIfExists('theses');
    }
};