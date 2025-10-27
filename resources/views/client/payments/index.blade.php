<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إدارة المدفوعات - نظام إدارة الاشتراكات</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-purple-600">إدارة المدفوعات</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('client.subscriptions') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
                        الاشتراكات
                    </a>
                    <a href="{{ route('client.dashboard') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm">
                        لوحة التحكم
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-red-600 px-3 py-2 rounded-md text-sm">
                            تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Breadcrumb & Back Button -->
        <x-breadcrumb :items="[
            ['label' => 'إدارة المدفوعات', 'url' => '']
        ]" />

        <x-back-button :url="route('client.dashboard')" label="العودة للوحة التحكم" />

        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    💳 إدارة المدفوعات والفواتير
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    تتبع فواتيرك ومدفوعاتك وسجل المعاملات المالية
                </p>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Total Bills -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-bold">📄</span>
                            </div>
                        </div>
                        <div class="mr-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    إجمالي الفواتير
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $billStats['total'] }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paid Bills -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-bold">✅</span>
                            </div>
                        </div>
                        <div class="mr-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    فواتير مدفوعة
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $billStats['paid'] }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Due Bills -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-bold">⚠️</span>
                            </div>
                        </div>
                        <div class="mr-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    فواتير مستحقة
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $billStats['due'] }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Payments -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-bold">💰</span>
                            </div>
                        </div>
                        <div class="mr-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    إجمالي المبلغ المدفوع
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    ${{ number_format($paymentStats['total_amount'], 2) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex">
                    <button onclick="switchTab('bills')" 
                            class="tab-button w-1/2 py-4 px-6 text-center border-b-2 border-blue-500 font-medium text-sm text-blue-600 focus:outline-none">
                        📄 الفواتير
                    </button>
                    <button onclick="switchTab('payments')" 
                            class="tab-button w-1/2 py-4 px-6 text-center border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none">
                        💳 المدفوعات
                    </button>
                </nav>
            </div>

            <!-- Bills Tab Content -->
            <div id="bills-tab" class="tab-content active">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">سجل الفواتير</h3>
                    
                    <div class="space-y-4">
                        @forelse($allBills as $bill)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="text-sm font-medium text-gray-900">
                                                {{ $bill->subscription_name }}
                                            </h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($bill->status == 'paid') bg-green-100 text-green-800
                                                @elseif($bill->status == 'due') bg-red-100 text-red-800
                                                @elseif($bill->status == 'upcoming') bg-blue-100 text-blue-800
                                                @elseif($bill->status == 'cancelled') bg-gray-100 text-gray-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                @if($bill->status == 'paid') مدفوعة
                                                @elseif($bill->status == 'due') مستحقة
                                                @elseif($bill->status == 'upcoming') قادمة
                                                @elseif($bill->status == 'cancelled') ملغاة
                                                @else {{ $bill->status }} @endif
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500 mb-2">{{ $bill->description }}</p>
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span>تاريخ الاستحقاق: {{ $bill->due_date->format('Y-m-d') }}</span>
                                            <span class="font-medium text-lg text-gray-900">${{ number_format($bill->amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 flex justify-end space-x-2 space-x-reverse">
                                    <button onclick="viewBillDetails({{ $bill->id }})" 
                                            class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                        👁️ عرض التفاصيل
                                    </button>
                                    @if($bill->status == 'due')
                                        <button class="inline-flex items-center px-3 py-1 border border-blue-300 shadow-sm text-xs font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100">
                                            💳 دفع الآن
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="text-gray-400 text-6xl mb-4">📄</div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد فواتير</h3>
                                <p class="text-gray-500">لم يتم إصدار أي فواتير بعد</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Payments Tab Content -->
            <div id="payments-tab" class="tab-content">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">سجل المدفوعات</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        المعاملة
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
                                @forelse($allPayments as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $payment->subscription_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $payment->description }}</div>
                                            <div class="text-xs text-blue-600 font-mono">{{ $payment->transaction_id }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">${{ number_format($payment->amount, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $payment->payment_method }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $payment->created_at->format('Y-m-d') }}</div>
                                            <div class="text-xs text-gray-500">{{ $payment->created_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($payment->status == 'completed') bg-green-100 text-green-800
                                                @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($payment->status == 'failed') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                @if($payment->status == 'completed') مكتملة
                                                @elseif($payment->status == 'pending') معلقة
                                                @elseif($payment->status == 'failed') فاشلة
                                                @else {{ $payment->status }} @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button onclick="viewPaymentReceipt({{ $payment->id }})" 
                                                    class="text-blue-600 hover:text-blue-900">
                                                🧾 الإيصال
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="text-gray-400 text-6xl mb-4">💳</div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مدفوعات</h3>
                                            <p class="text-gray-500">لم يتم إجراء أي مدفوعات بعد</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bill Details Modal -->
    <div id="billDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full m-4">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">تفاصيل الفاتورة</h3>
                    <button onclick="hideBillDetailsModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div id="billDetailsContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Receipt Modal -->
    <div id="paymentReceiptModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full m-4">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">إيصال الدفع</h3>
                    <button onclick="hidePaymentReceiptModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div id="paymentReceiptContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active styling from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.add('active');
            
            // Add active styling to clicked tab button
            event.target.classList.remove('border-transparent', 'text-gray-500');
            event.target.classList.add('border-blue-500', 'text-blue-600');
        }

        function viewBillDetails(billId) {
            fetch(`/client/payments/bill-details/${billId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const bill = data.bill;
                        const content = `
                            <div class="space-y-4">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900">${bill.subscription_name}</h4>
                                    <p class="text-sm text-gray-500">${bill.description}</p>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-500">رقم الفاتورة:</span>
                                        <span class="text-gray-900">#${bill.id}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-500">تاريخ الإصدار:</span>
                                        <span class="text-gray-900">${bill.issue_date}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-500">تاريخ الاستحقاق:</span>
                                        <span class="text-gray-900">${bill.due_date}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-500">الحالة:</span>
                                        <span class="text-gray-900">${getStatusInArabic(bill.status)}</span>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 pt-4">
                                    <h5 class="font-medium text-gray-900 mb-2">تفاصيل المبلغ</h5>
                                    ${bill.items.map(item => `
                                        <div class="flex justify-between text-sm mb-1">
                                            <span>${item.description}</span>
                                            <span>$${item.amount.toFixed(2)}</span>
                                        </div>
                                    `).join('')}
                                    <div class="border-t border-gray-200 mt-2 pt-2 flex justify-between font-medium">
                                        <span>الإجمالي</span>
                                        <span>$${bill.total.toFixed(2)}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                        document.getElementById('billDetailsContent').innerHTML = content;
                        document.getElementById('billDetailsModal').classList.remove('hidden');
                        document.getElementById('billDetailsModal').classList.add('flex');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('حدث خطأ أثناء تحميل تفاصيل الفاتورة', 'error');
                });
        }

        function viewPaymentReceipt(paymentId) {
            fetch(`/client/payments/receipt/${paymentId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const payment = data.payment;
                        const content = `
                            <div class="space-y-4">
                                <div class="text-center border-b border-gray-200 pb-4">
                                    <h4 class="text-lg font-medium text-gray-900">إيصال دفع</h4>
                                    <p class="text-sm text-gray-500">رقم الإيصال: ${payment.receipt_number}</p>
                                </div>
                                
                                <div class="space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-500">الاشتراك:</span>
                                        <span class="text-gray-900">${payment.subscription_name}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-500">طريقة الدفع:</span>
                                        <span class="text-gray-900">${payment.payment_method}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-500">رقم المعاملة:</span>
                                        <span class="text-gray-900 font-mono">${payment.transaction_id}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-500">تاريخ الدفع:</span>
                                        <span class="text-gray-900">${payment.payment_date}</span>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span>المبلغ الأساسي</span>
                                        <span>$${payment.amount.toFixed(2)}</span>
                                    </div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span>الضريبة</span>
                                        <span>$${payment.tax.toFixed(2)}</span>
                                    </div>
                                    <div class="border-t border-gray-200 mt-2 pt-2 flex justify-between font-medium">
                                        <span>الإجمالي المدفوع</span>
                                        <span>$${payment.total.toFixed(2)}</span>
                                    </div>
                                </div>

                                <div class="mt-4 text-center">
                                    <button onclick="printReceipt()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        🖨️ طباعة الإيصال
                                    </button>
                                </div>
                            </div>
                        `;
                        document.getElementById('paymentReceiptContent').innerHTML = content;
                        document.getElementById('paymentReceiptModal').classList.remove('hidden');
                        document.getElementById('paymentReceiptModal').classList.add('flex');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('حدث خطأ أثناء تحميل إيصال الدفع', 'error');
                });
        }

        function hideBillDetailsModal() {
            document.getElementById('billDetailsModal').classList.add('hidden');
            document.getElementById('billDetailsModal').classList.remove('flex');
        }

        function hidePaymentReceiptModal() {
            document.getElementById('paymentReceiptModal').classList.add('hidden');
            document.getElementById('paymentReceiptModal').classList.remove('flex');
        }

        function getStatusInArabic(status) {
            const statusMap = {
                'paid': 'مدفوعة',
                'due': 'مستحقة',
                'upcoming': 'قادمة',
                'cancelled': 'ملغاة'
            };
            return statusMap[status] || status;
        }

        function printReceipt() {
            window.print();
        }

        function showMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-md ${type === 'success' ? 'bg-green-100 text-green-700 border border-green-400' : 'bg-red-100 text-red-700 border border-red-400'}`;
            messageDiv.textContent = message;
            
            document.body.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }
    </script>
</body>
</html>