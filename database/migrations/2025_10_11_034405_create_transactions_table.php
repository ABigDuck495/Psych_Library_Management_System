<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            
            // User who borrowed the item
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Polymorphic relationship for the borrowed item
            $table->unsignedBigInteger('borrowable_id');
            $table->string('borrowable_type'); // App\Models\BookCopy or App\Models\ThesisCopy
            
            // Transaction details
            $table->enum('transaction_status', [
                'requested',  
                'borrowed', 
                'returned', 
                'overdue', 
                'cancelled'
            ])->default('requested');
            $table->dateTime('borrow_date');
            $table->dateTime('due_date');
            $table->dateTime('return_date')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['borrowable_type', 'borrowable_id']);
            $table->index('transaction_status');
            $table->index('due_date');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};