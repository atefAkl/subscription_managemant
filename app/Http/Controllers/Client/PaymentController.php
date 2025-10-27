<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Constructor - authentication is handled by route middleware
    }

    public function index()
    {
        $user = Auth::user();

        // جلب جميع الاشتراكات مع الفواتير والمدفوعات
        $subscriptions = $user->subscriptions()
            ->with(['subscription_request'])
            ->get();

        // إنشاء بيانات تجريبية للفواتير والمدفوعات
        $allBills = collect([
            (object)[
                'id' => 1,
                'subscription_name' => 'اشتراك iPhone Pro',
                'amount' => 299.99,
                'due_date' => now()->addDays(15),
                'status' => 'upcoming',
                'description' => 'فاتورة شهرية - فبراير 2024'
            ],
            (object)[
                'id' => 2,
                'subscription_name' => 'اشتراك iPhone Standard',
                'amount' => 199.99,
                'due_date' => now()->subDays(5),
                'status' => 'due',
                'description' => 'فاتورة شهرية - يناير 2024'
            ],
            (object)[
                'id' => 3,
                'subscription_name' => 'اشتراك iPhone Pro',
                'amount' => 299.99,
                'due_date' => now()->subDays(20),
                'status' => 'paid',
                'description' => 'فاتورة شهرية - ديسمبر 2023'
            ]
        ]);

        $allPayments = collect([
            (object)[
                'id' => 1,
                'subscription_name' => 'اشتراك iPhone Pro',
                'amount' => 299.99,
                'payment_method' => 'بطاقة ائتمان',
                'transaction_id' => 'TXN_' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'status' => 'completed',
                'created_at' => now()->subDays(20),
                'description' => 'دفع فاتورة ديسمبر 2023'
            ],
            (object)[
                'id' => 2,
                'subscription_name' => 'اشتراك iPhone Standard',
                'amount' => 199.99,
                'payment_method' => 'تحويل بنكي',
                'transaction_id' => 'TXN_' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'status' => 'completed',
                'created_at' => now()->subDays(50),
                'description' => 'دفع فاتورة نوفمبر 2023'
            ]
        ]);

        // إحصائيات الفواتير
        $billStats = [
            'total' => $allBills->count(),
            'paid' => $allBills->where('status', 'paid')->count(),
            'cancelled' => $allBills->where('status', 'cancelled')->count(),
            'due' => $allBills->where('status', 'due')->count(),
            'upcoming' => $allBills->where('status', 'upcoming')->count(),
        ];

        // إحصائيات المدفوعات
        $paymentStats = [
            'total' => $allPayments->count(),
            'total_amount' => $allPayments->sum('amount'),
            'successful' => $allPayments->where('status', 'completed')->count(),
            'pending' => $allPayments->where('status', 'pending')->count(),
        ];

        return view('client.payments.index', compact(
            'subscriptions',
            'allBills',
            'allPayments',
            'billStats',
            'paymentStats'
        ));
    }

    public function billDetails($billId)
    {
        // بيانات تجريبية لتفاصيل الفاتورة
        $billDetails = [
            'id' => $billId,
            'subscription_name' => 'اشتراك iPhone Pro',
            'amount' => 299.99,
            'tax' => 45.00,
            'total' => 344.99,
            'due_date' => now()->addDays(15)->format('Y-m-d'),
            'issue_date' => now()->subDays(15)->format('Y-m-d'),
            'status' => 'upcoming',
            'description' => 'فاتورة شهرية - فبراير 2024',
            'items' => [
                ['description' => 'خدمة iPhone Pro الشهرية', 'amount' => 299.99],
                ['description' => 'ضريبة القيمة المضافة (15%)', 'amount' => 45.00]
            ]
        ];

        return response()->json([
            'success' => true,
            'bill' => $billDetails
        ]);
    }

    public function paymentReceipt($paymentId)
    {
        // بيانات تجريبية لإيصال الدفع
        $receiptDetails = [
            'id' => $paymentId,
            'receipt_number' => 'RCP_' . str_pad($paymentId, 8, '0', STR_PAD_LEFT),
            'subscription_name' => 'اشتراك iPhone Pro',
            'amount' => 299.99,
            'tax' => 45.00,
            'total' => 344.99,
            'payment_method' => 'بطاقة ائتمان',
            'transaction_id' => 'TXN_' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
            'payment_date' => now()->subDays(20)->format('Y-m-d H:i:s'),
            'status' => 'completed'
        ];

        return response()->json([
            'success' => true,
            'payment' => $receiptDetails
        ]);
    }
}
