<?php
// app/Http/Controllers/Admin/LoginAttemptController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoginAttemptController extends Controller
{
    public function index(Request $request)
    {
        $query = LoginAttempt::with('user');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('successful', $request->status === 'success');
        }

        // Date filter
        if ($request->filled('date_from')) {
            $query->where('attempted_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('attempted_at', '<=', $request->date_to . ' 23:59:59');
        }

        $attempts = $query->orderBy('attempted_at', 'desc')->paginate(50);

        // Statistics
        $totalAttempts = LoginAttempt::count();
        $successfulCount = LoginAttempt::where('successful', true)->count();
        $failedCount = LoginAttempt::where('successful', false)->count();
        
        // Count locked accounts (users with 5+ failed attempts in last 30 minutes)
        $lockedAccountsCount = DB::table('login_attempts')
            ->select('email', DB::raw('COUNT(*) as attempts'))
            ->where('successful', false)
            ->where('attempted_at', '>=', Carbon::now()->subMinutes(30))
            ->groupBy('email')
            ->having('attempts', '>=', 5)
            ->count();

        return view('admin.login-attempts', compact(
            'attempts', 
            'totalAttempts',
            'successfulCount', 
            'failedCount',
            'lockedAccountsCount'
        ));
    }

    public function destroy(LoginAttempt $loginAttempt)
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $loginAttempt->delete();

        return redirect()->route('admin.login-attempts')
            ->with('success', 'Login attempt record deleted successfully.');
    }

    public function cleanup()
    {
        if (!in_array(auth()->user()->role, ['admin', 'super-admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $deletedCount = LoginAttempt::where('attempted_at', '<', Carbon::now()->subDays(30))->delete();

        return redirect()->route('admin.login-attempts')
            ->with('success', "Cleaned up {$deletedCount} login attempt records older than 30 days.");
    }
}