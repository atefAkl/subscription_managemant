<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>اختبار Ajax للمدفوعات</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; }
        button { padding: 10px 15px; margin: 5px; cursor: pointer; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        #log { background: #f8f9fa; padding: 10px; margin-top: 10px; height: 300px; overflow-y: scroll; }
    </style>
</head>
<body>
    <h1>اختبار Ajax للمدفوعات</h1>
    
    <div class="test-section">
        <h3>معلومات الجلسة:</h3>
        <p><strong>CSRF Token:</strong> <span id="csrf-token">{{ csrf_token() }}</span></p>
        <p><strong>User ID:</strong> {{ Auth::id() ?? 'غير مسجل الدخول' }}</p>
        <p><strong>Role:</strong> {{ Auth::user()->role ?? 'غير محدد' }}</p>
    </div>

    <div class="test-section">
        <h3>اختبار المدفوعات:</h3>
        <input type="number" id="paymentId" value="1" placeholder="Payment ID" style="padding: 8px;">
        <br><br>
        <button onclick="testVerifyPayment()">اختبار تأكيد الدفعة</button>
        <button onclick="testRejectPayment()">اختبار رفض الدفعة</button>
        <button onclick="testPaymentDetails()">اختبار تفاصيل الدفعة</button>
        <button onclick="testPendingPayments()">اختبار صفحة المدفوعات المعلقة</button>
    </div>

    <div class="test-section">
        <h3>سجل العمليات:</h3>
        <button onclick="clearLog()">مسح السجل</button>
        <div id="log"></div>
    </div>

    <script>
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function log(message, type = 'info') {
            const timestamp = new Date().toLocaleTimeString();
            const logDiv = $('#log');
            logDiv.append(`<div class="${type}">[${timestamp}] ${message}</div>`);
            logDiv.scrollTop(logDiv[0].scrollHeight);
        }

        function clearLog() {
            $('#log').empty();
        }

        function testVerifyPayment() {
            const paymentId = $('#paymentId').val();
            log(`محاولة تأكيد الدفعة رقم: ${paymentId}`, 'info');
            
            $.ajax({
                url: `/admin/payments/${paymentId}/verify`,
                type: 'POST',
                dataType: 'json',
                data: {
                    admin_notes: 'اختبار تأكيد من صفحة الاختبار'
                },
                beforeSend: function(xhr) {
                    log('إرسال طلب التأكيد...', 'info');
                },
                success: function(data) {
                    log(`نجح الطلب: ${JSON.stringify(data)}`, 'success');
                },
                error: function(xhr, status, error) {
                    log(`فشل الطلب - Status: ${xhr.status}`, 'error');
                    log(`Response: ${xhr.responseText}`, 'error');
                    log(`Error: ${error}`, 'error');
                }
            });
        }

        function testRejectPayment() {
            const paymentId = $('#paymentId').val();
            log(`محاولة رفض الدفعة رقم: ${paymentId}`, 'info');
            
            $.ajax({
                url: `/admin/payments/${paymentId}/reject`,
                type: 'POST',
                dataType: 'json',
                data: {
                    reason: 'اختبار رفض من صفحة الاختبار'
                },
                success: function(data) {
                    log(`نجح الطلب: ${JSON.stringify(data)}`, 'success');
                },
                error: function(xhr, status, error) {
                    log(`فشل الطلب - Status: ${xhr.status}`, 'error');
                    log(`Response: ${xhr.responseText}`, 'error');
                }
            });
        }

        function testPaymentDetails() {
            const paymentId = $('#paymentId').val();
            log(`محاولة جلب تفاصيل الدفعة رقم: ${paymentId}`, 'info');
            
            $.ajax({
                url: `/admin/payments/${paymentId}/details`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    log(`نجح الطلب: ${JSON.stringify(data)}`, 'success');
                },
                error: function(xhr, status, error) {
                    log(`فشل الطلب - Status: ${xhr.status}`, 'error');
                    log(`Response: ${xhr.responseText}`, 'error');
                }
            });
        }

        function testPendingPayments() {
            log('محاولة الوصول لصفحة المدفوعات المعلقة...', 'info');
            window.open('/admin/payments/pending', '_blank');
        }

        // Test on page load
        $(document).ready(function() {
            log('تم تحميل الصفحة بنجاح', 'success');
            log('CSRF Token: ' + $('meta[name="csrf-token"]').attr('content'), 'info');
        });
    </script>
</body>
</html>