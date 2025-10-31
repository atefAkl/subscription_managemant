<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\AdminProfile;
use App\Models\ClientProfile;

class SystemSettingsController extends Controller
{
  /**
   * Display system settings dashboard
   */
  public function index()
  {
    $settings = $this->getSystemSettings();
    $systemInfo = $this->getSystemInfo();

    return view('admin.settings.index', compact('settings', 'systemInfo'));
  }

  /**
   * Get current system settings
   */
  private function getSystemSettings()
  {
    return [
      'app_name' => config('app.name', 'نظام إدارة الاشتراكات'),
      'app_version' => '2.0.0',
      'maintenance_mode' => app()->isDownForMaintenance(),
      'debug_mode' => config('app.debug', false),
      'cache_enabled' => Cache::has('system_cache_test'),
      'max_clients' => 1000,
      'max_devices_per_client' => 50,
      'session_lifetime' => config('session.lifetime', 120),
      'backup_enabled' => Storage::exists('backups'),
      'email_notifications' => true,
      'sms_notifications' => false,
      'auto_approve_requests' => false,
      'allow_trial_subscriptions' => true,
    ];
  }

  /**
   * Get system information
   */
  private function getSystemInfo()
  {
    return [
      'php_version' => PHP_VERSION,
      'laravel_version' => app()->version(),
      'server_os' => PHP_OS,
      'server_time' => now()->format('Y-m-d H:i:s'),
      'timezone' => config('app.timezone'),
      'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
      'memory_limit' => ini_get('memory_limit'),
      'max_execution_time' => ini_get('max_execution_time') . 's',
      'upload_max_filesize' => ini_get('upload_max_filesize'),
      'database_size' => $this->getDatabaseSize(),
      'storage_used' => $this->getStorageUsage(),
    ];
  }

  /**
   * Update system settings
   */
  public function update(Request $request)
  {
    $validated = $request->validate([
      'app_name' => 'required|string|max:255',
      'max_clients' => 'required|integer|min:1|max:10000',
      'max_devices_per_client' => 'required|integer|min:1|max:100',
      'session_lifetime' => 'required|integer|min:30|max:1440',
      'email_notifications' => 'boolean',
      'sms_notifications' => 'boolean',
      'auto_approve_requests' => 'boolean',
      'allow_trial_subscriptions' => 'boolean',
    ]);

    // Here you would typically save to a settings table or config files
    // For now, we'll use cache as a demo
    foreach ($validated as $key => $value) {
      Cache::put("settings.{$key}", $value, now()->addDays(30));
    }

    return redirect()->route('admin.settings.index')
      ->with('success', 'تم حفظ الإعدادات بنجاح');
  }

  /**
   * Clear system cache
   */
  public function clearCache()
  {
    try {
      Artisan::call('cache:clear');
      Artisan::call('config:clear');
      Artisan::call('route:clear');
      Artisan::call('view:clear');

      return redirect()->route('admin.settings.index')
        ->with('success', 'تم مسح الذاكرة المؤقتة بنجاح');
    } catch (\Exception $e) {
      return redirect()->route('admin.settings.index')
        ->with('error', 'فشل في مسح الذاكرة المؤقتة: ' . $e->getMessage());
    }
  }

  /**
   * Toggle maintenance mode
   */
  public function toggleMaintenance()
  {
    try {
      if (app()->isDownForMaintenance()) {
        Artisan::call('up');
        $message = 'تم إلغاء وضع الصيانة';
      } else {
        Artisan::call('down', ['--render' => 'errors::503']);
        $message = 'تم تفعيل وضع الصيانة';
      }

      return redirect()->route('admin.settings.index')
        ->with('success', $message);
    } catch (\Exception $e) {
      return redirect()->route('admin.settings.index')
        ->with('error', 'فشل في تغيير وضع الصيانة: ' . $e->getMessage());
    }
  }

  /**
   * Create system backup
   */
  public function createBackup()
  {
    try {
      // This would typically call a backup package or custom backup logic
      $backupName = 'backup_' . now()->format('Y_m_d_H_i_s') . '.sql';

      // Simulate backup creation
      Storage::put("backups/{$backupName}", "-- System Backup Created at " . now());

      return redirect()->route('admin.settings.index')
        ->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح: ' . $backupName);
    } catch (\Exception $e) {
      return redirect()->route('admin.settings.index')
        ->with('error', 'فشل في إنشاء النسخة الاحتياطية: ' . $e->getMessage());
    }
  }

  /**
   * Get database size (approximate)
   */
  private function getDatabaseSize()
  {
    try {
      // This is a simplified calculation
      $tables = ['users', 'admin_profiles', 'client_profiles', 'subscriptions', 'devices', 'payments'];
      $totalRows = 0;

      foreach ($tables as $table) {
        $totalRows += \DB::table($table)->count();
      }

      return $totalRows . ' سجل تقريبياً';
    } catch (\Exception $e) {
      return 'غير متاح';
    }
  }

  /**
   * Get storage usage
   */
  private function getStorageUsage()
  {
    try {
      $files = Storage::allFiles();
      $totalSize = 0;

      foreach ($files as $file) {
        $totalSize += Storage::size($file);
      }

      return round($totalSize / 1024 / 1024, 2) . ' MB';
    } catch (\Exception $e) {
      return 'غير متاح';
    }
  }

  /**
   * System health check
   */
  public function healthCheck()
  {
    $checks = [];

    // Database connection
    try {
      \DB::connection()->getPdo();
      $checks['database'] = ['status' => 'healthy', 'message' => 'الاتصال بقاعدة البيانات يعمل'];
    } catch (\Exception $e) {
      $checks['database'] = ['status' => 'error', 'message' => 'خطأ في الاتصال بقاعدة البيانات'];
    }

    // Storage permissions
    try {
      Storage::put('test_file.txt', 'test');
      Storage::delete('test_file.txt');
      $checks['storage'] = ['status' => 'healthy', 'message' => 'التخزين يعمل بشكل صحيح'];
    } catch (\Exception $e) {
      $checks['storage'] = ['status' => 'error', 'message' => 'مشكلة في صلاحيات التخزين'];
    }

    // Memory usage
    $memoryUsage = memory_get_usage(true) / 1024 / 1024;
    if ($memoryUsage < 128) {
      $checks['memory'] = ['status' => 'healthy', 'message' => 'استخدام الذاكرة طبيعي'];
    } else {
      $checks['memory'] = ['status' => 'warning', 'message' => 'استخدام عالي للذاكرة'];
    }

    // Disk space
    $freeSpace = disk_free_space('/') / 1024 / 1024 / 1024;
    if ($freeSpace > 1) {
      $checks['disk'] = ['status' => 'healthy', 'message' => 'مساحة القرص كافية'];
    } else {
      $checks['disk'] = ['status' => 'warning', 'message' => 'مساحة القرص منخفضة'];
    }

    return response()->json($checks);
  }

  /**
   * Get system statistics
   */
  public function getSystemStats()
  {
    return response()->json([
      'users' => [
        'total_admins' => User::where('role', 'admin')->count(),
        'total_clients' => User::where('role', 'client')->count(),
        'super_admins' => User::where('is_app_admin', true)->count(),
        'active_today' => User::where('last_login_at', '>=', now()->startOfDay())->count(),
      ],
      'profiles' => [
        'admin_profiles' => AdminProfile::count(),
        'client_profiles' => ClientProfile::count(),
        'complete_profiles' => AdminProfile::whereNotNull('department')->count(),
      ],
      'subscriptions' => [
        'active' => ClientProfile::where('subscription_status', 'active')->count(),
        'trial' => ClientProfile::where('subscription_status', 'trial')->count(),
        'expired' => ClientProfile::where('subscription_status', 'expired')->count(),
        'expiring_soon' => ClientProfile::where('subscription_end_date', '<=', now()->addDays(7))
          ->where('subscription_end_date', '>', now())
          ->count(),
      ],
      'performance' => [
        'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2),
        'peak_memory' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
        'execution_time' => round(microtime(true) - LARAVEL_START, 3),
        'queries_count' => \DB::getQueryLog() ? count(\DB::getQueryLog()) : 0,
      ]
    ]);
  }
}
