<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateThesesTableAndDropThesisDeptTable extends Migration
{
    public function up()
    {
        // Remove the dept_id column from the theses table
        Schema::table('theses', function (Blueprint $table) {
            $table->dropColumn('dept_id');
        });

        // Drop the thesis_dept table
        Schema::dropIfExists('thesis_dept');
    }

    public function down()
    {
        // Add the dept_id column back to the theses table
        Schema::table('theses', function (Blueprint $table) {
            $table->unsignedBigInteger('dept_id')->nullable();
        });

        // Recreate the thesis_dept table
        Schema::create('thesis_dept', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('thesis_id');
            $table->unsignedBigInteger('dept_id');
            $table->timestamps();

            $table->foreign('thesis_id')->references('id')->on('theses')->onDelete('cascade');
            $table->foreign('dept_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }
}
