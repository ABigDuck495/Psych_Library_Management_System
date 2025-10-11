<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('thesis_dept', function (Blueprint $table) {
            $table->id();
            $table->string('dept_name')->unique();
            $table->string('dept_code')->unique()->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            // Performance indexes
            $table->index('dept_name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('thesis_dept');
    }
};