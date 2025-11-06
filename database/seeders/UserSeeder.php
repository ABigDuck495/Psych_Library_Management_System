<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Use updateOrCreate to avoid duplicates
            $user = User::updateOrCreate(
                ['email' => 'usertester@gmail.com'],
                [
                    'university_id' => '23-2667',
                    'username' => 'User Tester',
                    'first_name' => 'User',
                    'last_name' => 'Tester',
                    'role' => 'user',
                    'phone_number' => '09774484907',
                    'account_status' => 'Active',
                    'password' => Hash::make('P@assw0rd'),
                    'user_type' => 'student',
                ]
            );

            // Update or create student record without hardcoded ID
            DB::table('students')->updateOrInsert(
                ['id' => $user->id],
                [
                    'academic_program' => 'Undergraduate',
                    'department' => 'BSIT',
                ]
            );
        });
    }
}