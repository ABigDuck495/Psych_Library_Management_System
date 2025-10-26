<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('copy_id')->constrained('book_copies', 'copy_id');
            $table->timestamp('borrow_date')->useCurrent();
            $table->timestamp('due_date');
            $table->timestamp('return_date')->nullable();
            $table->string('copy_type');
            $table->enum('transaction_status', ['requested','borrowed', 'returned', 'overdue'])->default('borrowed');
            $table->timestamps();

            // Performance indexes
            $table->index('user_id');
            $table->index('copy_id');
            $table->index('transaction_status');
            $table->index('due_date');
            $table->index('return_date');
            $table->index(['transaction_status', 'due_date']);
            $table->index(['user_id', 'transaction_status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};