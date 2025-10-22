<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function adminDashboard()
    {
        $user = Auth::user();

        // Check if user is admin
        if (!$user->isAdmin()) {
            return redirect()->route('client.dashboard')
                ->with('error', 'غير مسموح لك بالوصول إلى لوحة الإدارة');
        }

        // Get statistics for admin dashboard
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalClients = User::where('role', 'client')->count();
        $recentUsers = User::latest()->limit(5)->get();

        return view('dashboard.admin', compact(
            'user',
            'totalUsers',
            'totalAdmins',
            'totalClients',
            'recentUsers'
        ));
    }

    /**
     * Client Dashboard
     */
    public function clientDashboard()
    {
        $user = Auth::user();

        // Check if user is client
        if (!$user->isClient()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'غير مسموح لك بالوصول إلى لوحة العميل');
        }

        // Get client-specific data
        $accountInfo = [
            'subscription_status' => 'نشط',
            'subscription_plan' => 'الباقة المتقدمة',
            'expires_at' => now()->addDays(30)->format('Y-m-d'),
            'storage_used' => '15 جيجا',
            'storage_limit' => '20 جيجا'
        ];

        return view('dashboard.client', compact('user', 'accountInfo'));
    }
}
