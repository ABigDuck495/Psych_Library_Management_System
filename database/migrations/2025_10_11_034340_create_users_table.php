<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('employees');
        Schema::dropIfExists('students');
        Schema::dropIfExists('users');

        //base profile for all users
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('university_id')->unique();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('role', ['user', 'librarian', 'admin', 'super-admin'])->default('user');
            $table->string('phone_number')->nullable();
            $table->enum('account_status', ['Active', 'Inactive'])->default('Active');
            $table->timestamp('registration_date')->useCurrent();
            $table->timestamp('last_login_date')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->string('password');
            $table->enum('user_type', ['student', 'employee'])->nullable(); // To track which type of user
            $table->rememberToken();
            $table->timestamps();

            //indexes
            $table->index('username');
            $table->index('email');
            $table->index('account_status');
            $table->index('user_type');
            $table->index('registration_date');
        });

        //students table (extends users)
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id');
            $table->foreignId('id')->unique()->constrained('users')->onDelete('cascade');
            $table->enum('academic_program', ['Undergraduate', 'Masters', 'PhD'])->default('Undergraduate');
            $table->string('department');
            $table->timestamps();

            //indexes
            $table->index('academic_program');
            $table->index('department');
        });

        //employee table (extends users)
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->foreignId('id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('department');
            $table->string('position_title');
            $table->timestamps();

            //indexes
            $table->index('department');
            $table->index('position_title');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
        Schema::dropIfExists('students');
        Schema::dropIfExists('users');
    }
};