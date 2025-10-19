<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('thesis_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_id')
                ->constrained('theses')
                ->onDelete('cascade'); //makes it so it deletes the copies when you delete a thesis
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            //indexes for performance
            $table->index('thesis_id');
            $table->index('is_available');
            $table->index(['thesis_id', 'is_available']); // For finding available copies
        });     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thesis_copies');
    }
};
