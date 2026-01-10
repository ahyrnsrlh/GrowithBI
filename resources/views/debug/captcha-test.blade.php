<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reCAPTCHA v3 Test Page</title>
    <script src="https://www.google.com/recaptcha/api.js?render={{ $siteKey }}"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 { color: #333; margin-bottom: 8px; }
        h2 { color: #666; font-size: 18px; margin-top: 0; }
        .status { padding: 12px; border-radius: 6px; margin: 16px 0; }
        .status.success { background: #d4edda; color: #155724; }
        .status.error { background: #f8d7da; color: #721c24; }
        .status.warning { background: #fff3cd; color: #856404; }
        .status.info { background: #d1ecf1; color: #0c5460; }
        button {
            background: #4285f4;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        button:hover { background: #3367d6; }
        button:disabled { background: #ccc; cursor: not-allowed; }
        pre {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 16px;
            border-radius: 6px;
            overflow-x: auto;
            font-size: 13px;
        }
        .key-info { font-family: monospace; word-break: break-all; }
        #log { max-height: 400px; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="card">
        <h1>🔐 reCAPTCHA v3 Debug Tool</h1>
        <h2>Test token generation and verification</h2>
        
        <div class="status info">
            <strong>Site Key:</strong> 
            <span class="key-info">{{ $siteKey ?: 'NOT CONFIGURED' }}</span>
        </div>
        
        <div id="grecaptcha-status" class="status warning">
            ⏳ Checking reCAPTCHA status...
        </div>
    </div>

    <div class="card">
        <h2>🧪 Test Actions</h2>
        <button onclick="testLogin()">Test Login Action</button>
        <button onclick="testForgotPassword()">Test Forgot Password</button>
        <button onclick="testOTP()">Test OTP Verification</button>
        <button onclick="clearLog()" style="background: #6c757d;">Clear Log</button>
    </div>

    <div class="card">
        <h2>📋 Debug Log</h2>
        <pre id="log">Waiting for test...</pre>
    </div>

    <div class="card">
        <h2>❓ Common Issues</h2>
        <ul>
            <li><strong>browser-error:</strong> Site key not registered for this domain (localhost). Go to <a href="https://www.google.com/recaptcha/admin" target="_blank">reCAPTCHA Admin</a> and add "localhost" to your domains.</li>
            <li><strong>invalid-input-secret:</strong> Secret key in .env is wrong or malformed.</li>
            <li><strong>timeout-or-duplicate:</strong> Token expired or already used. Tokens are single-use.</li>
            <li><strong>Score is null:</strong> Token invalid, verification failed before scoring.</li>
        </ul>
    </div>

    <script>
        const siteKey = '{{ $siteKey }}';
        const logEl = document.getElementById('log');
        const statusEl = document.getElementById('grecaptcha-status');
        
        function log(message, data = null) {
            const timestamp = new Date().toISOString().substr(11, 8);
            let text = `[${timestamp}] ${message}`;
            if (data) {
                text += '\n' + JSON.stringify(data, null, 2);
            }
            logEl.textContent = text + '\n\n' + logEl.textContent;
        }
        
        function clearLog() {
            logEl.textContent = 'Log cleared...';
        }

        // Check if grecaptcha loaded
        document.addEventListener('DOMContentLoaded', function() {
            log('Page loaded, checking grecaptcha...');
            
            if (!siteKey) {
                statusEl.className = 'status error';
                statusEl.innerHTML = '❌ Site key not configured!';
                log('ERROR: No site key found');
                return;
            }
            
            if (typeof grecaptcha === 'undefined') {
                statusEl.className = 'status error';
                statusEl.innerHTML = '❌ grecaptcha not loaded! Check console for errors.';
                log('ERROR: grecaptcha is undefined');
                return;
            }
            
            grecaptcha.ready(function() {
                statusEl.className = 'status success';
                statusEl.innerHTML = '✅ reCAPTCHA loaded successfully!';
                log('SUCCESS: grecaptcha is ready');
            });
        });

        async function executeAndVerify(action) {
            log(`Starting test for action: ${action}`);
            
            if (typeof grecaptcha === 'undefined') {
                log('ERROR: grecaptcha not available');
                return;
            }

            try {
                log('Executing grecaptcha.execute()...');
                const token = await grecaptcha.execute(siteKey, { action: action });
                
                log('Token generated:', {
                    length: token.length,
                    preview: token.substring(0, 50) + '...'
                });
                
                // Verify with backend
                log('Sending to backend for verification...');
                
                const response = await fetch('/debug/captcha/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        token: token,
                        action: action
                    })
                });
                
                const result = await response.json();
                log('Verification result:', result);
                
                // Show summary
                if (result.google_raw_response?.success) {
                    log(`✅ SUCCESS! Score: ${result.google_raw_response.score}`);
                } else {
                    log(`❌ FAILED! Errors: ${JSON.stringify(result.google_raw_response?.['error-codes'] || [])}`);
                }
                
            } catch (error) {
                log('ERROR:', { message: error.message, stack: error.stack });
            }
        }

        function testLogin() {
            executeAndVerify('login');
        }

        function testForgotPassword() {
            executeAndVerify('forgot_password');
        }

        function testOTP() {
            executeAndVerify('otp_verification');
        }
    </script>
</body>
</html>
