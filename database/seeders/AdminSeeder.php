<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            DB::table('users')->insert([
                'id' => 1,
                'university_id' => '23-2634',
                'username' => 'Admin Tester',
                'email' => 'admintester@gmail.com',
                'first_name' => 'Admin',
                'last_name' => 'Tester',
                'role' => 'admin',
                'phone_number' => '09064093019',
                'account_status' => 'Active',
                'password' => Hash::make('P@ssw0rd'),
                'user_type' => 'student',
            ]);

            DB::table('students')->insert([
                'id' => 1,
                'academic_program' => 'Undergraduate',
                'department' => 'BSIT',
            ]);
        });
    }
}
