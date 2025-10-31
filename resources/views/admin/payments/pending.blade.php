<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>التحقق من المدفوعات المعلقة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-right ml-2"></i>
                        العودة للوحة التحكم
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">التحقق من المدفوعات</h1>
                </div>
                
                <div class="flex items-center space-x-3 space-x-reverse">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-clock ml-1"></i>
                        آخر تحديث: {{ now()->format('H:i') }}
                    </div>
                    <button onclick="location.reload()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-sync ml-1"></i>
                        تحديث
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Pending -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي المعلق</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $payments->total() }}</p>
                    </div>
                </div>
            </div>

            <!-- Today's Payments -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">دفعات اليوم</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $payments->where('created_at', '>=', today())->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Amount -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-calculator text-blue-600 text-xl"></i>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي المبلغ</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ number_format($payments->sum('amount')) }} ج.م
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-list ml-2"></i>
                    المدفوعات المعلقة
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    قائمة بالمدفوعات التي تحتاج للمراجعة والتحقق
                </p>
            </div>

            @if($payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    رقم الدفعة
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    العميل
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    طلب الاشتراك
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    المبلغ
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    طريقة الدفع
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    التاريخ
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الحالة
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الإجراءات
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $payment->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="mr-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $payment->user->name ?? 'غير محدد' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $payment->user->email ?? '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($payment->subscriptionRequest)
                                            <div>
                                                <div class="font-medium">{{ $payment->subscriptionRequest->subscription_name }}</div>
                                                <div class="text-gray-500">{{ $payment->subscriptionRequest->device_count }} أجهزة</div>
                                            </div>
                                        @else
                                            <span class="text-gray-400">لا يوجد طلب مرتبط</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        {{ number_format($payment->amount) }} ج.م
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $payment->payment_method ?? 'غير محدد' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->created_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payment->status === 'pending_verification')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock ml-1"></i>
                                                بانتظار التحقق
                                            </span>
                                        @elseif($payment->status === 'verified')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check ml-1"></i>
                                                محقق
                                            </span>
                                        @elseif($payment->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times ml-1"></i>
                                                مرفوض
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2 space-x-reverse">
                                        <button onclick="verifyPayment({{ $payment->id }})" 
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition-colors">
                                            <i class="fas fa-check ml-1"></i>
                                            تأكيد الدفع
                                        </button>
                                        <button onclick="viewDetails({{ $payment->id }})" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition-colors">
                                            <i class="fas fa-eye ml-1"></i>
                                            التفاصيل
                                        </button>
                                        <button onclick="rejectPayment({{ $payment->id }})" 
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition-colors">
                                            <i class="fas fa-times ml-1"></i>
                                            رفض
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($payments->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $payments->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <i class="fas fa-money-bill-wave text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مدفوعات معلقة</h3>
                    <p class="text-gray-500 mb-4">جميع المدفوعات تم التحقق منها أو لا توجد مدفوعات جديدة</p>
                    <button onclick="location.reload()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-sync ml-1"></i>
                        إعادة تحديث
                    </button>
                </div>
            @endif
        </div>
    </main>

    <!-- Payment Details Modal -->
    <div id="paymentDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/5 xl:w-1/2 shadow-xl rounded-lg bg-white max-h-screen overflow-y-auto">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4 sticky top-0 bg-white pb-4 border-b">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-file-invoice-dollar text-blue-600 ml-2"></i>
                        إيصال الدفع - تفاصيل كاملة
                    </h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-full transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="paymentDetailsContent" class="max-h-96 overflow-y-auto">
                    <!-- Payment details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function verifyPayment(paymentId) {
            if (confirm('هل أنت متأكد من تأكيد هذه الدفعة؟')) {
                $.ajax({
                    url: `/admin/payments/${paymentId}/verify`,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        admin_notes: 'تم التحقق من المدفوعة'
                    },
                    success: function(data) {
                        if (data.success) {
                            alert('تم تأكيد الدفعة بنجاح');
                            location.reload();
                        } else {
                            alert(data.message || 'حدث خطأ في تأكيد الدفعة');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText);
                        console.error('Status:', status);
                        console.error('Error:', error);
                        
                        if (xhr.status === 419) {
                            alert('انتهت صلاحية الجلسة. يرجى إعادة تحميل الصفحة.');
                        } else if (xhr.status === 404) {
                            alert('المدفوعة غير موجودة.');
                        } else {
                            alert('حدث خطأ في الاتصال: ' + (xhr.responseJSON?.message || error));
                        }
                    }
                });
            }
        }

        function rejectPayment(paymentId) {
            const reason = prompt('سبب رفض الدفعة (اختياري):');
            if (reason !== null) {
                $.ajax({
                    url: `/admin/payments/${paymentId}/reject`,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        reason: reason
                    },
                    success: function(data) {
                        if (data.success) {
                            alert('تم رفض الدفعة');
                            location.reload();
                        } else {
                            alert(data.message || 'حدث خطأ في رفض الدفعة');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText);
                        if (xhr.status === 419) {
                            alert('انتهت صلاحية الجلسة. يرجى إعادة تحميل الصفحة.');
                        } else {
                            alert('حدث خطأ في الاتصال: ' + (xhr.responseJSON?.message || error));
                        }
                    }
                });
            }
        }

        function viewDetails(paymentId) {
            $.ajax({
                url: `/admin/payments/${paymentId}/details`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        const payment = data.payment;
                        const content = `
                            <!-- Payment Receipt Header -->
                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6 rounded-t-lg -m-5 mb-5">
                                <div class="text-center">
                                    <i class="fas fa-receipt text-3xl mb-2"></i>
                                    <h2 class="text-xl font-bold">إيصال الدفع</h2>
                                    <p class="text-blue-100">رقم الإيصال: #${payment.id}</p>
                                </div>
                            </div>

                            <!-- Payment Details -->
                            <div class="space-y-6">
                                <!-- Basic Info Grid -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <label class="text-sm font-medium text-gray-500">رقم الدفعة</label>
                                        <p class="text-lg font-bold text-blue-600">#${payment.id}</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <label class="text-sm font-medium text-gray-500">المبلغ المدفوع</label>
                                        <p class="text-lg font-bold text-green-600">${payment.amount} ج.م</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <label class="text-sm font-medium text-gray-500">طريقة الدفع</label>
                                        <p class="text-gray-900 font-medium">${payment.payment_method}</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <label class="text-sm font-medium text-gray-500">حالة الدفعة</label>
                                        <p class="text-gray-900 font-medium">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                ${payment.status === 'pending_verification' ? 'bg-yellow-100 text-yellow-800' : 
                                                  payment.status === 'verified' ? 'bg-green-100 text-green-800' : 
                                                  'bg-red-100 text-red-800'}">
                                                ${payment.status_label}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Timeline Info -->
                                <div class="border-t pt-4">
                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <i class="fas fa-history ml-2"></i>
                                        التسلسل الزمني
                                    </h4>
                                    <div class="bg-gray-50 p-3 rounded-lg space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">تاريخ إنشاء الدفعة:</span>
                                            <span class="text-gray-900 font-medium">${payment.created_at}</span>
                                        </div>
                                        ${payment.paid_at ? `
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">تاريخ الدفع:</span>
                                            <span class="text-gray-900 font-medium">${payment.paid_at}</span>
                                        </div>
                                        ` : ''}
                                        ${payment.verified_at ? `
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">تاريخ التحقق:</span>
                                            <span class="text-green-600 font-medium">${payment.verified_at}</span>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>

                                <!-- Customer Info -->
                                <div class="border-t pt-4">
                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <i class="fas fa-user ml-2"></i>
                                        معلومات العميل
                                    </h4>
                                    <div class="bg-blue-50 p-3 rounded-lg grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">اسم العميل</label>
                                            <p class="text-gray-900 font-medium">${payment.user?.name || 'غير محدد'}</p>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">البريد الإلكتروني</label>
                                            <p class="text-gray-900">${payment.user?.email || 'غير محدد'}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Subscription Info -->
                                ${payment.subscription_request ? `
                                <div class="border-t pt-4">
                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <i class="fas fa-tags ml-2"></i>
                                        تفاصيل الاشتراك
                                    </h4>
                                    <div class="bg-purple-50 p-3 rounded-lg grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">نوع الاشتراك</label>
                                            <p class="text-gray-900 font-medium">${payment.subscription_request.subscription_name}</p>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">عدد الأجهزة</label>
                                            <p class="text-gray-900">${payment.subscription_request.device_count} أجهزة</p>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">السعر المتفق عليه</label>
                                            <p class="text-gray-900 font-bold text-green-600">${payment.subscription_request.quoted_price} ج.م</p>
                                        </div>
                                    </div>
                                </div>
                                ` : ''}

                                ${payment.transaction_id ? `
                                    <!-- Transaction Details -->
                                    <div class="border-t pt-4">
                                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                            <i class="fas fa-exchange-alt ml-2"></i>
                                            تفاصيل المعاملة
                                        </h4>
                                        <div class="bg-yellow-50 p-3 rounded-lg">
                                            <label class="text-sm font-medium text-gray-500">رقم المرجع</label>
                                            <p class="text-gray-900 font-mono">${payment.transaction_id}</p>
                                        </div>
                                    </div>
                                ` : ''}

                                ${payment.receipt_path ? `
                                    <!-- Payment Receipt -->
                                    <div class="border-t pt-4">
                                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                            <i class="fas fa-image ml-2"></i>
                                            إيصال الدفع المرفق
                                        </h4>
                                        <div class="bg-green-50 p-3 rounded-lg">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <i class="fas fa-file-image text-green-600 text-2xl ml-3"></i>
                                                    <div>
                                                        <p class="text-gray-900 font-medium">إيصال الدفع</p>
                                                        <p class="text-gray-600 text-sm">تم رفع الإيصال بواسطة العميل</p>
                                                    </div>
                                                </div>
                                                <button onclick="viewReceiptImage('${payment.receipt_path}')" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                                                    <i class="fas fa-eye ml-1"></i>
                                                    عرض الإيصال
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                ` : `
                                    <!-- No Receipt -->
                                    <div class="border-t pt-4">
                                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                            <i class="fas fa-exclamation-triangle text-orange-500 ml-2"></i>
                                            إيصال الدفع
                                        </h4>
                                        <div class="bg-orange-50 p-3 rounded-lg">
                                            <p class="text-gray-700">لم يتم رفع إيصال الدفع من قبل العميل</p>
                                        </div>
                                    </div>
                                `}

                                ${payment.notes ? `
                                    <!-- Admin Notes -->
                                    <div class="border-t pt-4">
                                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                            <i class="fas fa-sticky-note ml-2"></i>
                                            ملاحظات إدارية
                                        </h4>
                                        <div class="bg-orange-50 p-3 rounded-lg">
                                            <p class="text-gray-900">${payment.notes}</p>
                                        </div>
                                    </div>
                                ` : ''}

                                <!-- Receipt Actions -->
                                <div class="border-t pt-4 flex justify-between">
                                    <button onclick="printReceipt()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                        <i class="fas fa-print ml-1"></i>
                                        طباعة الإيصال
                                    </button>
                                    <button onclick="downloadReceipt(${payment.id})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                        <i class="fas fa-download ml-1"></i>
                                        تحميل PDF
                                    </button>
                                </div>

                                <!-- Receipt Footer -->
                                <div class="text-center text-gray-500 text-sm border-t pt-3">
                                    <p>تم إنشاء هذا الإيصال تلقائياً بواسطة نظام إدارة الاشتراكات</p>
                                    <p class="mt-1">تاريخ الطباعة: ${new Date().toLocaleString('ar-EG')}</p>
                                </div>
                            </div>
                        `;
                        $('#paymentDetailsContent').html(content);
                        $('#paymentDetailsModal').removeClass('hidden');
                    } else {
                        alert('حدث خطأ في تحميل تفاصيل الدفعة');
                    }
                },
                error: function(xhr, status, error) {
                    alert('حدث خطأ في الاتصال');
                }
            });
        }

        function closeModal() {
            $('#paymentDetailsModal').addClass('hidden');
        }

        function printReceipt() {
            // Create a new window for printing
            const printWindow = window.open('', '_blank');
            const content = $('#paymentDetailsContent').html();
            
            printWindow.document.write(`
                <!DOCTYPE html>
                <html lang="ar" dir="rtl">
                <head>
                    <meta charset="utf-8">
                    <title>إيصال دفع</title>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            margin: 20px; 
                            direction: rtl;
                        }
                        .bg-gradient-to-r { 
                            background: #4F46E5; 
                            color: white; 
                            padding: 20px; 
                            text-align: center; 
                            margin-bottom: 20px;
                        }
                        .grid { 
                            display: grid; 
                            grid-template-columns: 1fr 1fr; 
                            gap: 15px; 
                            margin: 15px 0;
                        }
                        .bg-gray-50, .bg-blue-50, .bg-yellow-50, .bg-orange-50 { 
                            background: #f9f9f9; 
                            padding: 10px; 
                            border-radius: 5px; 
                            border: 1px solid #e5e5e5;
                        }
                        .text-lg { font-size: 18px; }
                        .font-bold { font-weight: bold; }
                        .text-blue-600 { color: #2563eb; }
                        .text-green-600 { color: #16a34a; }
                        .border-t { 
                            border-top: 1px solid #e5e5e5; 
                            margin-top: 15px; 
                            padding-top: 15px; 
                        }
                        .text-center { text-align: center; }
                        .text-sm { font-size: 14px; }
                        @media print {
                            body { margin: 0; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    ${content.replace(/onclick="[^"]*"/g, '').replace(/class="bg-[^"]*btn[^"]*"/g, 'class="no-print"')}
                </body>
                </html>
            `);
            
            printWindow.document.close();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        }

        function downloadReceipt(paymentId) {
            // For now, we'll use the print function
            // In a real application, you'd implement PDF generation on the server
            alert('يتم تحضير ملف PDF...');
            
            // You could implement server-side PDF generation here
            // window.location.href = `/admin/payments/${paymentId}/pdf`;
            
            // For now, let's use print as PDF
            printReceipt();
        }

        function viewReceiptImage(receiptPath) {
            // Create a modal to display the receipt image
            const imageModal = `
                <div id="receiptImageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-[60]">
                    <div class="relative max-w-4xl max-h-full p-4">
                        <button onclick="closeReceiptModal()" class="absolute top-2 right-2 text-white bg-red-600 hover:bg-red-700 rounded-full p-2 z-10">
                            <i class="fas fa-times"></i>
                        </button>
                        <img src="/storage/${receiptPath}" alt="إيصال الدفع" class="max-w-full max-h-full rounded-lg shadow-lg">
                        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2">
                            <button onclick="downloadReceiptImage('/storage/${receiptPath}')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                <i class="fas fa-download ml-1"></i>
                                تحميل الإيصال
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            $('body').append(imageModal);
        }

        function closeReceiptModal() {
            $('#receiptImageModal').remove();
        }

        function downloadReceiptImage(imagePath) {
            const link = document.createElement('a');
            link.href = imagePath;
            link.download = `receipt_${Date.now()}.jpg`;
            link.click();
        }

        // Auto refresh every 30 seconds
        setInterval(() => {
            const indicator = document.querySelector('.fas.fa-clock');
            if (indicator) {
                indicator.style.color = '#10B981';
                setTimeout(() => {
                    indicator.style.color = '';
                }, 500);
            }
        }, 30000);
    </script>
</body>
</html>