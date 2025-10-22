<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with services and pricing
     */
    public function index()
    {
        $services = [
            [
                'title' => 'الباقة الأساسية',
                'description' => 'مناسبة للمشاريع الصغيرة والشركات الناشئة',
                'price' => '99',
                'currency' => 'ريال',
                'period' => 'شهرياً',
                'features' => [
                    'حتى 10 مستخدمين',
                    'مساحة تخزين 5 جيجا',
                    'دعم فني عبر البريد الإلكتروني',
                    'تقارير أساسية'
                ]
            ],
            [
                'title' => 'الباقة المتقدمة',
                'description' => 'مناسبة للشركات متوسطة الحجم',
                'price' => '199',
                'currency' => 'ريال',
                'period' => 'شهرياً',
                'features' => [
                    'حتى 50 مستخدم',
                    'مساحة تخزين 20 جيجا',
                    'دعم فني على مدار 24/7',
                    'تقارير متقدمة',
                    'إدارة المخزون'
                ]
            ],
            [
                'title' => 'الباقة الاحترافية',
                'description' => 'مناسبة للشركات الكبيرة والمؤسسات',
                'price' => '399',
                'currency' => 'ريال',
                'period' => 'شهرياً',
                'features' => [
                    'مستخدمين غير محدودين',
                    'مساحة تخزين غير محدودة',
                    'دعم فني مخصص',
                    'تقارير شاملة ومخصصة',
                    'إدارة متقدمة للمخزون',
                    'واجهة برمجة التطبيقات API'
                ]
            ]
        ];

        return view('home', compact('services'));
    }
}
