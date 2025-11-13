<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KeyManagementController extends Controller
{
    /**
     * عرض صفحة إدارة المفاتيح
     */
    public function index()
    {
        $keys = Key::all();

        return view('admin.key-management.index');
    }
}
