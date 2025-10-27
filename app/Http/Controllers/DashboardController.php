<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SubscriptionRequest;
use App\Models\Subscription;
use App\Models\Device;
use App\Models\Payment;
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
        if ($user->role !== 'admin') {
            return redirect()->route('client.dashboard')
                ->with('error', 'غير مسموح لك بالوصول إلى لوحة الإدارة');
        }

        // Get statistics for admin dashboard
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalClients = User::where('role', 'client')->count();
        $recentUsers = User::latest()->limit(5)->get();

        // Subscription statistics
        $subscriptionStats = [
            'pending_requests' => SubscriptionRequest::where('status', 'pending')->count(),
            'quoted_requests' => SubscriptionRequest::where('status', 'quoted')->count(),
            'paid_requests' => SubscriptionRequest::where('status', 'paid')->count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_devices' => Device::count(),
            'active_devices' => Device::where('status', 'active')->count(),
            'pending_devices' => Device::where('status', 'pending')->count(),
            'pending_payments' => Payment::where('status', 'pending_verification')->count(),
        ];

        // Recent activities
        $recentRequests = SubscriptionRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact(
            'user',
            'totalUsers',
            'totalAdmins',
            'totalClients',
            'recentUsers',
            'subscriptionStats',
            'recentRequests'
        ));
    }

    /**
     * Client Dashboard
     */
    public function clientDashboard(Request $request)
    {
        $user = Auth::user();

        // Check if user is client
        if ($user->role !== 'client') {
            return redirect()->route('home')
                ->with('error', 'غير مسموح لك بالوصول إلى لوحة العميل');
        }

        // Get real client statistics
        $stats = [
            'active_subscriptions' => Subscription::where('user_id', $user->id)->where('status', 'active')->count(),
            'total_devices' => Device::whereHas('subscription', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count(),
            'pending_requests' => SubscriptionRequest::where('user_id', $user->id)->whereIn('status', ['pending', 'quoted'])->count(),
            'total_payments' => Payment::where('user_id', $user->id)->count(),
            'verified_payments' => Payment::where('user_id', $user->id)->where('status', 'verified')->count(),
            'pending_payments' => Payment::where('user_id', $user->id)->where('status', 'pending_verification')->count(),
        ];

        // Get recent subscription requests
        $recentRequests = SubscriptionRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Get active subscriptions
        $activeSubscriptions = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('devices')
            ->limit(3)
            ->get();

        return view('dashboard.client', compact('user', 'stats', 'recentRequests', 'activeSubscriptions'));
    }
}
