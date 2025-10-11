<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->enum('status', ['pending', 'paid', 'waived'])->default('pending');
            $table->string('reason')->nullable();
            $table->timestamp('issued_date')->useCurrent();
            $table->timestamp('paid_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Performance indexes
            $table->index('user_id');
            $table->index('transaction_id');
            $table->index('status');
            $table->index('paid_date');
            $table->index(['user_id', 'status']);
            $table->index('issued_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penalties');
    }
};