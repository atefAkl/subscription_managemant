<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdminProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
  public function __construct()
  {
    // Middleware handled by routes
  }

  /**
   * Display a listing of admin users only
   */
  public function index()
  {
    $users = User::where('role', 'admin')
      ->with('adminProfile')
      ->orderBy('created_at', 'desc')
      ->paginate(15);

    $stats = [
      'total_admins' => User::where('role', 'admin')->count(),
      'super_admins' => User::where('is_app_admin', true)->count(),
      'recent_admins' => User::where('role', 'admin')
        ->where('created_at', '>=', now()->subDays(7))
        ->count(),
      'active_sessions' => User::where('role', 'admin')
        ->where('last_login_at', '>=', now()->subDays(1))
        ->count(),
    ];

    return view('admin.users.index', compact('users', 'stats'));
  }

  /**
   * Show the form for creating a new user
   */
  public function create()
  {
    return view('admin.users.create');
  }

  /**
   * Store a newly created admin user
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name'            => 'required|string|max:255',
      'user_name'            => 'required|string|max:30',
      'email'           => 'required|string|email|max:255|unique:users',
      'password'        => 'required|string|min:8|confirmed',
      'phone'           => 'nullable|string|max:16',
      'address'         => 'nullable|string|max:1000',
      'notes'           => 'nullable|string|max:1000',
      // Admin Profile fields
      'department'      => 'nullable|string|max:255',
      'position'        => 'nullable|string|max:255',
      'access_level'    => 'nullable|integer|between:1,4',
      'profile_notes'   => 'nullable|string|max:1000',
    ]);

    DB::beginTransaction();

    try {
      $user = User::create([
        'name' => $validated['name'],
        'user_name' => $validated['user_name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'admin', // Always admin for this controller
        'phone' => $validated['phone'] ?? null,
        'address' => $validated['address'] ?? null,
        'notes' => $validated['notes'] ?? null,
        'is_app_admin' => $validated['is_app_admin'] ?? false,
      ]);
      DB::commit();

      // Create admin profile
      AdminProfile::create([
        'user_id' => $user->id,
        'department' => $validated['department'] ?? null,
        'position' => $validated['position'] ?? null,
        'access_level' => $validated['access_level'],
        'notes' => $validated['profile_notes'] ?? null,
      ]);

      DB::commit();

      return redirect()->route('admin.users.index')
        ->with('success', 'تم إنشاء المدير بنجاح - رقم الموظف: ' . $user->serial_number);
    } catch (\Exception $e) {
      DB::rollback();
      return redirect()->back()->withInput()->withErrors(['error' => 'فشل إنشاء المستخدم: ' . $e->getMessage()]);
    }
  }

  /**
   * Display the specified admin user
   */
  public function show(User $user)
  {
    // Ensure user is an admin
    if (!$user->isAdmin()) {
      abort(404, 'المستخدم غير موجود');
    }

    $user->load(['adminProfile']);

    // Get admin activities and statistics
    $stats = [
      'login_count' => $user->last_login_at ? 1 : 0, // Can be enhanced with login history table
      'profile_completeness' => $this->calculateProfileCompleteness($user),
      'permissions_count' => count($user->adminProfile?->getPermissions() ?? []),
      'department' => $user->adminProfile?->department ?? 'غير محدد',
    ];

    return view('admin.users.show', compact('user', 'stats'));
  }

  /**
   * Calculate profile completeness percentage
   */
  private function calculateProfileCompleteness(User $user): int
  {
    $fields = [
      'name' => !empty($user->name),
      'email' => !empty($user->email),
      'phone' => !empty($user->phone),
      'address' => !empty($user->address),
      'employee_number' => !empty($user->employee_number),
      'department' => !empty($user->adminProfile?->department),
      'position' => !empty($user->adminProfile?->position),
    ];

    $completed = array_sum($fields);
    $total = count($fields);

    return round(($completed / $total) * 100);
  }

  /**
   * Show the form for editing the user
   */
  public function edit(User $user)
  {
    return view('admin.users.edit', compact('user'));
  }

  /**
   * Update the specified admin user
   */
  public function update(Request $request, User $user)
  {
    // Ensure user is an admin
    if (!$user->isAdmin()) {
      abort(404, 'المستخدم غير موجود');
    }

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
      'password' => 'nullable|string|min:8|confirmed',
      'phone' => 'nullable|string|max:255',
      'address' => 'nullable|string|max:1000',
      'notes' => 'nullable|string|max:1000',
      'is_app_admin' => 'nullable|boolean',
      // Admin Profile fields
      'department' => 'nullable|string|max:255',
      'position' => 'nullable|string|max:255',
      'access_level' => 'required|integer|min:1|max:4',
      'profile_notes' => 'nullable|string|max:1000',
    ]);

    // Only super admins can modify super admin status
    if (isset($validated['is_app_admin']) && !Auth::user()->isAppAdmin()) {
      unset($validated['is_app_admin']);
    }

    // Prevent removing last super admin
    if (
      isset($validated['is_app_admin']) && !$validated['is_app_admin'] &&
      $user->isAppAdmin() && User::where('is_app_admin', true)->count() <= 1
    ) {
      return redirect()->back()
        ->with('error', 'لا يمكن إزالة صلاحية آخر مدير تطبيق في النظام');
    }

    $updateData = [
      'name' => $validated['name'],
      'email' => $validated['email'],
      'phone' => $validated['phone'] ?? null,
      'address' => $validated['address'] ?? null,
      'notes' => $validated['notes'] ?? null,
    ];

    if (isset($validated['is_app_admin'])) {
      $updateData['is_app_admin'] = $validated['is_app_admin'];
    }

    if (!empty($validated['password'])) {
      $updateData['password'] = Hash::make($validated['password']);
    }

    $user->update($updateData);

    // Update or create admin profile
    $user->adminProfile()->updateOrCreate(
      ['user_id' => $user->id],
      [
        'department' => $validated['department'] ?? null,
        'position' => $validated['position'] ?? null,
        'access_level' => $validated['access_level'],
        'notes' => $validated['profile_notes'] ?? null,
      ]
    );

    return redirect()->route('admin.users.show', $user)
      ->with('success', 'تم تحديث بيانات المدير بنجاح');
  }

  /**
   * Remove the specified admin user
   */
  public function destroy(User $user)
  {
    // Ensure user is an admin
    if (!$user->isAdmin()) {
      abort(404, 'المستخدم غير موجود');
    }

    // Prevent deleting the last admin
    if (User::where('role', 'admin')->count() <= 1) {
      return redirect()->route('admin.users.index')
        ->with('error', 'لا يمكن حذف آخر مدير في النظام');
    }

    // Prevent deleting the last super admin
    if ($user->isAppAdmin() && User::where('is_app_admin', true)->count() <= 1) {
      return redirect()->route('admin.users.index')
        ->with('error', 'لا يمكن حذف آخر مدير تطبيق في النظام');
    }

    // Prevent admins from deleting themselves
    if ($user->id === Auth::id()) {
      return redirect()->route('admin.users.index')
        ->with('error', 'لا يمكنك حذف حسابك الخاص');
    }

    $employeeNumber = $user->employee_number;
    $user->delete();

    return redirect()->route('admin.users.index')
      ->with('success', 'تم حذف المدير بنجاح (رقم الموظف: ' . $employeeNumber . ')');
  }

  /**
   * Get admin activities and system actions
   */
  public function activities(User $user)
  {
    // Ensure user is an admin
    if (!$user->isAdmin()) {
      return response()->json(['error' => 'المستخدم غير موجود'], 404);
    }

    $activities = collect();

    // Add profile updates (this would be enhanced with audit logs)
    if ($user->adminProfile) {
      $activities->push([
        'type' => 'profile_created',
        'description' => 'إنشاء بروفايل إداري',
        'date' => $user->adminProfile->created_at,
        'status' => 'completed',
        'details' => [
          'department' => $user->adminProfile->department,
          'access_level' => $user->adminProfile->getAccessLevelText(),
        ]
      ]);

      if ($user->adminProfile->updated_at != $user->adminProfile->created_at) {
        $activities->push([
          'type' => 'profile_updated',
          'description' => 'تحديث البروفايل الإداري',
          'date' => $user->adminProfile->updated_at,
          'status' => 'completed',
        ]);
      }
    }

    // Add login activity
    if ($user->last_login_at) {
      $activities->push([
        'type' => 'login',
        'description' => 'تسجيل دخول للنظام',
        'date' => $user->last_login_at,
        'status' => 'completed',
      ]);
    }

    // Add account creation
    $activities->push([
      'type' => 'account_created',
      'description' => 'إنشاء حساب إداري',
      'date' => $user->created_at,
      'status' => 'completed',
      'details' => [
        'employee_number' => $user->employee_number,
        'is_super_admin' => $user->isAppAdmin(),
      ]
    ]);

    $activities = $activities->sortByDesc('date')->take(20);

    return response()->json($activities);
  }

  /**
   * Get admin permissions for display
   */
  public function permissions(User $user)
  {
    // Ensure user is an admin
    if (!$user->isAdmin()) {
      return response()->json(['error' => 'المستخدم غير موجود'], 404);
    }

    $permissions = $user->adminProfile?->getPermissions() ?? [];
    $allPermissions = AdminProfile::PERMISSIONS;

    $formattedPermissions = [];
    foreach ($allPermissions as $key => $description) {
      $formattedPermissions[] = [
        'key' => $key,
        'description' => $description,
        'granted' => in_array($key, $permissions)
      ];
    }

    return response()->json([
      'permissions' => $formattedPermissions,
      'access_level' => $user->adminProfile?->getAccessLevelText() ?? 'غير محدد',
      'is_super_admin' => $user->isAppAdmin()
    ]);
  }
}
