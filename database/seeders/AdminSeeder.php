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
            // Use updateOrCreate to avoid duplicates
            $admin = User::updateOrCreate(
                ['email' => 'admintester@gmail.com'],
                [
                    'university_id' => '23-2634',
                    'username' => 'Admin Tester',
                    'first_name' => 'Admin',
                    'last_name' => 'Tester',
                    'role' => 'admin',
                    'phone_number' => '09064093019',
                    'account_status' => 'Active',
                    'password' => Hash::make('P@ssw0rd'),
                    'user_type' => 'student',
                ]
            );

            // Update or create student record without hardcoded ID
            DB::table('students')->updateOrInsert(
                ['id' => $admin->id],
                [
                    'academic_program' => 'Undergraduate',
                    'department' => 'BSIT',
                ]
            );
        });
    }
}