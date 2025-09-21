<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - GrowithBI</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
            background-color: #f8fafc;
        }
        .container {
            background-color: #ffffff;
            margin: 20px auto;
            padding: 0;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header .logo {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 10px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #4b5563;
            margin-bottom: 30px;
            line-height: 1.7;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }
        .alternative-link {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 4px solid #3b82f6;
        }
        .alternative-link p {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #6b7280;
        }
        .alternative-link code {
            background-color: #e5e7eb;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
            word-break: break-all;
        }
        .footer {
            background-color: #f8fafc;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #6b7280;
        }
        .footer .contact {
            font-size: 13px;
            color: #9ca3af;
        }
        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 25px 0;
        }
        .warning p {
            margin: 0;
            font-size: 14px;
            color: #92400e;
        }
        .security-info {
            background-color: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 8px;
            padding: 15px;
            margin: 25px 0;
        }
        .security-info p {
            margin: 0;
            font-size: 14px;
            color: #0c4a6e;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏦 Bank Indonesia KPW Lampung</div>
            <h1>GrowithBI</h1>
        </div>
        
        <div class="content">
            <div class="greeting">Halo!</div>
            
            <div class="message">
                Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda di platform GrowithBI - Program Magang Bank Indonesia KPW Lampung.
            </div>
            
            <div class="button-container">
                <a href="{{ $actionUrl }}" class="reset-button">
                    🔑 Reset Password
                </a>
            </div>
            
            <div class="alternative-link">
                <p><strong>Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda:</strong></p>
                <code>{{ $actionUrl }}</code>
            </div>
            
            <div class="security-info">
                <p><strong>🔒 Keamanan:</strong> Link reset password ini akan kedaluwarsa dalam <strong>{{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} menit</strong> untuk menjaga keamanan akun Anda.</p>
            </div>
            
            <div class="warning">
                <p><strong>⚠️ Penting:</strong> Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah dan akun Anda tetap aman.</p>
            </div>
            
            <div class="message">
                <strong>Langkah selanjutnya:</strong>
                <ol>
                    <li>Klik tombol "Reset Password" di atas</li>
                    <li>Masukkan password baru yang kuat</li>
                    <li>Konfirmasi password baru</li>
                    <li>Login dengan password baru Anda</li>
                </ol>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>GrowithBI - Program Magang Bank Indonesia</strong></p>
            <p>Bank Indonesia Kantor Perwakilan Wilayah Lampung</p>
            <div class="contact">
                <p>📧 Email: growithbi@bi.go.id | 📞 Telepon: (0721) 123 4567</p>
                <p>📍 Jl. Wolter Monginsidi No. 2, Bandar Lampung 35115</p>
            </div>
        </div>
    </div>
</body>
</html>