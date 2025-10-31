<!DOCTYPE html>
<html>
<head>
    <title>Payment Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Payment Verification Test</h1>
    
    <button onclick="testPaymentVerify()">Test Payment Verification</button>
    
    <script>
        function testPaymentVerify() {
            // Test with payment ID 1
            const paymentId = 1;
            
            fetch(`/admin/payments/${paymentId}/verify`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    admin_notes: 'Test verification'
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.text();
            })
            .then(text => {
                console.log('Raw response:', text);
                try {
                    const data = JSON.parse(text);
                    console.log('Parsed JSON:', data);
                    alert('Success: ' + JSON.stringify(data));
                } catch (e) {
                    console.error('Failed to parse JSON:', e);
                    alert('Raw response: ' + text);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error: ' + error.message);
            });
        }
    </script>
</body>
</html>