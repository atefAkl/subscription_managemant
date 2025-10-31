<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminProfile;
use App\Models\ClientProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
  /**
   * Display statistics dashboard
   */
  public function index()
  {
    $overview = $this->getOverviewStats();
    $charts = $this->getChartsData();

    return view('admin.statistics.index', compact('overview', 'charts'));
  }

  /**
   * Get overview statistics
   */
  private function getOverviewStats()
  {
    return [
      'users' => [
        'total' => User::count(),
        'admins' => User::where('role', 'admin')->count(),
        'clients' => User::where('role', 'client')->count(),
        'super_admins' => User::where('is_app_admin', true)->count(),
        'new_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
        'active_today' => User::where('last_login_at', '>=', now()->startOfDay())->count(),
      ],
      'subscriptions' => [
        'total' => ClientProfile::count(),
        'active' => ClientProfile::where('subscription_status', 'active')->count(),
        'trial' => ClientProfile::where('subscription_status', 'trial')->count(),
        'expired' => ClientProfile::where('subscription_status', 'expired')->count(),
        'suspended' => ClientProfile::where('subscription_status', 'suspended')->count(),
        'expiring_soon' => ClientProfile::where('subscription_end_date', '<=', now()->addDays(7))
          ->where('subscription_end_date', '>', now())
          ->count(),
      ],
      'revenue' => [
        'total_devices' => ClientProfile::sum('devices_count'),
        'average_devices' => round(ClientProfile::avg('devices_count'), 1),
        'basic_subscriptions' => ClientProfile::where('subscription_type', 'basic')->count(),
        'premium_subscriptions' => ClientProfile::where('subscription_type', 'premium')->count(),
        'enterprise_subscriptions' => ClientProfile::where('subscription_type', 'enterprise')->count(),
      ],
      'performance' => [
        'admin_utilization' => $this->getAdminUtilization(),
        'client_satisfaction' => $this->getClientSatisfaction(),
        'system_health' => $this->getSystemHealth(),
      ]
    ];
  }

  /**
   * Get charts data for dashboard
   */
  private function getChartsData()
  {
    return [
      'user_growth' => $this->getUserGrowthData(),
      'subscription_distribution' => $this->getSubscriptionDistribution(),
      'monthly_revenue' => $this->getMonthlyRevenueData(),
      'admin_activity' => $this->getAdminActivityData(),
      'client_activity' => $this->getClientActivityData(),
    ];
  }

  /**
   * Get user growth data for the last 12 months
   */
  private function getUserGrowthData()
  {
    $data = [];

    for ($i = 11; $i >= 0; $i--) {
      $month = now()->subMonths($i);
      $data[] = [
        'month' => $month->format('M Y'),
        'admins' => User::where('role', 'admin')
          ->whereMonth('created_at', $month->month)
          ->whereYear('created_at', $month->year)
          ->count(),
        'clients' => User::where('role', 'client')
          ->whereMonth('created_at', $month->month)
          ->whereYear('created_at', $month->year)
          ->count(),
      ];
    }

    return $data;
  }

  /**
   * Get subscription type distribution
   */
  private function getSubscriptionDistribution()
  {
    return [
      'basic' => ClientProfile::where('subscription_type', 'basic')->count(),
      'premium' => ClientProfile::where('subscription_type', 'premium')->count(),
      'enterprise' => ClientProfile::where('subscription_type', 'enterprise')->count(),
    ];
  }

  /**
   * Get monthly revenue simulation (since we don't have actual payment amounts)
   */
  private function getMonthlyRevenueData()
  {
    $data = [];

    // Simulate revenue based on subscription types and device counts
    $basicPrice = 100; // per device
    $premiumPrice = 200;
    $enterprisePrice = 300;

    for ($i = 11; $i >= 0; $i--) {
      $month = now()->subMonths($i);

      $basic = ClientProfile::where('subscription_type', 'basic')
        ->where('subscription_status', 'active')
        ->whereMonth('created_at', '<=', $month->month)
        ->sum('devices_count') * $basicPrice;

      $premium = ClientProfile::where('subscription_type', 'premium')
        ->where('subscription_status', 'active')
        ->whereMonth('created_at', '<=', $month->month)
        ->sum('devices_count') * $premiumPrice;

      $enterprise = ClientProfile::where('subscription_type', 'enterprise')
        ->where('subscription_status', 'active')
        ->whereMonth('created_at', '<=', $month->month)
        ->sum('devices_count') * $enterprisePrice;

      $data[] = [
        'month' => $month->format('M Y'),
        'basic' => $basic,
        'premium' => $premium,
        'enterprise' => $enterprise,
        'total' => $basic + $premium + $enterprise,
      ];
    }

    return $data;
  }

  /**
   * Get admin activity data
   */
  private function getAdminActivityData()
  {
    return AdminProfile::select('access_level', DB::raw('count(*) as count'))
      ->groupBy('access_level')
      ->get()
      ->mapWithKeys(function ($item) {
        $levels = [
          1 => 'محدود',
          2 => 'متوسط',
          3 => 'كامل',
          4 => 'سوبر أدمن'
        ];
        return [$levels[$item->access_level] ?? 'غير محدد' => $item->count];
      })->toArray();
  }

  /**
   * Get client activity data
   */
  private function getClientActivityData()
  {
    return [
      'payment_status' => ClientProfile::select('payment_status', DB::raw('count(*) as count'))
        ->groupBy('payment_status')
        ->get()
        ->pluck('count', 'payment_status')
        ->toArray(),
      'billing_cycle' => ClientProfile::select('billing_cycle', DB::raw('count(*) as count'))
        ->groupBy('billing_cycle')
        ->get()
        ->pluck('count', 'billing_cycle')
        ->toArray(),
    ];
  }

  /**
   * Calculate admin utilization rate
   */
  private function getAdminUtilization()
  {
    $totalAdmins = User::where('role', 'admin')->count();
    $activeAdmins = User::where('role', 'admin')
      ->where('last_login_at', '>=', now()->subDays(7))
      ->count();

    return $totalAdmins > 0 ? round(($activeAdmins / $totalAdmins) * 100, 1) : 0;
  }

  /**
   * Calculate client satisfaction (based on active subscriptions)
   */
  private function getClientSatisfaction()
  {
    $totalClients = ClientProfile::count();
    $activeClients = ClientProfile::where('subscription_status', 'active')->count();

    return $totalClients > 0 ? round(($activeClients / $totalClients) * 100, 1) : 0;
  }

  /**
   * Calculate system health score
   */
  private function getSystemHealth()
  {
    $factors = [];

    // Factor 1: User activity (30%)
    $totalUsers = User::count();
    $activeUsers = User::where('last_login_at', '>=', now()->subDays(7))->count();
    $factors['activity'] = $totalUsers > 0 ? ($activeUsers / $totalUsers) * 30 : 0;

    // Factor 2: Subscription health (40%)
    $totalSubscriptions = ClientProfile::count();
    $healthySubscriptions = ClientProfile::whereIn('subscription_status', ['active', 'trial'])->count();
    $factors['subscriptions'] = $totalSubscriptions > 0 ? ($healthySubscriptions / $totalSubscriptions) * 40 : 0;

    // Factor 3: System utilization (30%)
    $memoryUsage = memory_get_usage(true) / 1024 / 1024; // MB
    $memoryScore = $memoryUsage < 128 ? 30 : ($memoryUsage < 256 ? 20 : 10);
    $factors['system'] = $memoryScore;

    return round(array_sum($factors), 1);
  }

  /**
   * Export statistics report
   */
  public function exportReport(Request $request)
  {
    $format = $request->get('format', 'json');
    $data = [
      'generated_at' => now()->toISOString(),
      'overview' => $this->getOverviewStats(),
      'charts' => $this->getChartsData(),
    ];

    if ($format === 'json') {
      return response()->json($data);
    }

    // For CSV format
    if ($format === 'csv') {
      $filename = 'statistics_report_' . now()->format('Y_m_d_H_i_s') . '.csv';

      $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
      ];

      return response()->stream(function () use ($data) {
        $file = fopen('php://output', 'w');

        // Write headers
        fputcsv($file, ['Metric', 'Value']);

        // Write overview data
        foreach ($data['overview'] as $category => $metrics) {
          fputcsv($file, [$category, '']);
          foreach ($metrics as $key => $value) {
            fputcsv($file, ["  {$key}", $value]);
          }
        }

        fclose($file);
      }, 200, $headers);
    }

    return response()->json(['error' => 'Unsupported format'], 400);
  }

  /**
   * Get real-time statistics (AJAX)
   */
  public function getRealTimeStats()
  {
    return response()->json([
      'timestamp' => now()->toISOString(),
      'users_online' => $this->getUsersOnline(),
      'active_sessions' => $this->getActiveSessions(),
      'system_load' => $this->getSystemLoad(),
      'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2),
      'recent_activities' => $this->getRecentActivities(),
    ]);
  }

  /**
   * Get users online count (approximation)
   */
  private function getUsersOnline()
  {
    return User::where('last_login_at', '>=', now()->subMinutes(30))->count();
  }

  /**
   * Get active sessions count
   */
  private function getActiveSessions()
  {
    // This would typically come from session storage
    return rand(5, 25); // Simulated data
  }

  /**
   * Get system load
   */
  private function getSystemLoad()
  {
    if (function_exists('sys_getloadavg')) {
      $load = sys_getloadavg();
      return round($load[0], 2);
    }
    return null;
  }

  /**
   * Get recent activities
   */
  private function getRecentActivities()
  {
    $activities = [];

    // Recent user registrations
    $recentUsers = User::where('created_at', '>=', now()->subHours(24))
      ->latest()
      ->take(5)
      ->get(['name', 'role', 'created_at']);

    foreach ($recentUsers as $user) {
      $activities[] = [
        'type' => 'user_registration',
        'description' => "مستخدم جديد: {$user->name} ({$user->role})",
        'time' => $user->created_at->diffForHumans(),
      ];
    }

    // Recent profile updates
    $recentProfiles = AdminProfile::where('updated_at', '>=', now()->subHours(24))
      ->where('updated_at', '>', DB::raw('created_at'))
      ->latest('updated_at')
      ->take(3)
      ->with('user')
      ->get();

    foreach ($recentProfiles as $profile) {
      $activities[] = [
        'type' => 'profile_update',
        'description' => "تحديث بروفايل: {$profile->user->name}",
        'time' => $profile->updated_at->diffForHumans(),
      ];
    }

    return collect($activities)->sortByDesc('time')->values()->take(10)->all();
  }
}
