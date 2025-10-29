<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\BookCopy;
use App\Models\Employee;
use App\Models\ThesisCopy;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-users')->except(['index', 'show','editSelf', 'updateSelf']);
    }

    public function index()
    {
        $query = User::query();

        // Search
        if (request('search')) {
            $s = request('search');
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('username', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('university_id', 'like', "%{$s}%");
            });
        }

        if (request('role')) {
            $query->where('role', request('role'));
        }

        if (request('user_type')) {
            $query->where('user_type', request('user_type'));
        }

        if (request('account_status')) {
            $query->where('account_status', request('account_status'));
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('users.index', compact('users'));
    }
    
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => [
                    'required',
                    'email',
                    'unique:users',
                    'regex:/^[\w\.-]+@(?:gmail\.com|google\.com|yahoo\.com|clsu\.edu\.ph)$/i'
                ],
                'phone_number' => 'nullable|string|max:20',
                'university_id' => 'nullable|string|max:50|unique:users',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&]/',
                ],
                'role' => 'required|string|in:user,librarian,admin,super-admin',
                'user_type' => 'required|string|in:student,employee',
                'account_status' => 'required|string|in:Active,Inactive,Suspended',
                'position_title' => 'nullable|string|max:255',
                'academic_program' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255',
                'major_department' => 'nullable|string|max:255',
            ]);

            $user = User::create([
                'university_id' => $validated['university_id'] ?? null,
                'username' => $validated['username'],
                'email' => $validated['email'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'role' => $validated['role'],
                'account_status' => $validated['account_status'],
                'phone_number' => $validated['phone_number'] ?? null,
                'password' => Hash::make($validated['password']),
                'user_type' => $validated['user_type'],
                'registration_date' => now(),
            ]);

            if($validated['user_type'] === 'student'){
                Student::create([
                    'id' => $user->id,
                    'academic_program' => $validated['academic_program'],
                    'department' => $validated['major_department']
                ]);
            } elseif ($validated['user_type'] === 'employee'){
                Employee::create([
                    'id' => $user->id,
                    'position_title' => $validated['position_title'],
                    'department' => $validated['department']
                ]);
            }

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Failed to create user: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $user = User::with(['student', 'employee', 'transactions' => function($query) {
            $query->with(['borrowable' => function($morphTo) {
                $morphTo->morphWith([
                    BookCopy::class => ['book'],
                    ThesisCopy::class => ['thesis'],
                ]);
            }])->latest();
        }])->findOrFail($id);

        $details = $user->user_type === 'student' ? $user->student : 
                  ($user->user_type === 'employee' ? $user->employee : null);

        return view('users.show', compact('user', 'details'));
    }

   public function edit($id)
    {
        $authUser = auth()->user();
        
        // Allow access if user is admin OR editing their own profile
        if ($authUser->role !== 'admin' && $authUser->id != (int)$id) {
            abort(403);
        }

        $user = User::with(['student', 'employee'])->findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function editSelf()
    {
        $user = auth()->user();
        // This will call edit() with the user's own ID, which now passes the check
        return view('users.editself', compact('user'));
    }
    public function updateSelf(Request $request)
    {
        $authUser = auth()->user(); // Get the authenticated user

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $authUser->id,
            'phone_number' => 'nullable|string|max:20',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/[a-z]/',      // at least one lowercase letter
                'regex:/[A-Z]/',      // at least one uppercase letter
                'regex:/[0-9]/',      // at least one digit
                'regex:/[@$!%*#?&]/', // at least one special character
            ],
        ]);

                // Prepare update data
        $updateData = [
            'username' => $validated['username'],
            'phone_number' => $validated['phone_number'],
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Update the authenticated user's data
        $authUser->update($updateData);

        return redirect('/')->with('success', 'Your profile has been updated.');
    }



    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'phone_number' => 'nullable|string|max:20',
                'university_id' => 'nullable|string|max:50|unique:users,university_id,' . $user->id,
                'role' => 'required|string|in:user,librarian,admin,super-admin',
                'user_type' => 'required|string|in:student,employee',
                'account_status' => 'required|string|in:Active,Inactive,Suspended',
                'position_title' => 'nullable|required_if:user_type,employee|string|max:255',
                'academic_program' => 'nullable|required_if:user_type,student|string|max:255',
                'department' => 'nullable|string|max:255',
            ]);

            // Update user basic info
            $userData = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'username' => $validated['username'],
                'phone_number' => $validated['phone_number'],
                'university_id' => $validated['university_id'],
                'role' => $validated['role'],
                'user_type' => $validated['user_type'],
                'account_status' => $validated['account_status'],
            ];

            // Update password if provided
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // Update or create user type details
            if($validated['user_type'] === 'student'){
                Student::updateOrCreate(
                    ['id' => $user->id],
                    [
                        'academic_program' => $validated['academic_program'],
                        'department' => $validated['major_department']
                    ]
                );
                // Remove employee record if exists
                Employee::where('id', $user->id)->delete();
            } elseif ($validated['user_type'] === 'employee'){
                Employee::updateOrCreate(
                    ['id' => $user->id],
                    [
                        'position_title' => $validated['position_title'],
                        'department' => $validated['department']
                    ]
                );
                // Remove student record if exists
                Student::where('id', $user->id)->delete();
            }

            DB::commit();
            return redirect()->route('users.show', $user->id)
                             ->with('success', 'User updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Failed to update user: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        DB::transaction(function () use ($user) {
            // Delete related records
            if ($user->student) {
                $user->student->delete();
            }
            if ($user->employee) {
                $user->employee->delete();
            }
            
            $user->delete();
        });

        return redirect()->route('users.index')
                         ->with('success', 'User deleted successfully');
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->update(['account_status' => 'Active']);
        return redirect()->back()->with('success', 'User activated.');
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->update(['account_status' => 'Inactive']);
        return redirect()->back()->with('success', 'User deactivated.');
    }
}