<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Login - GrowithBI</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif; background-color: #F8FAFC; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #FFFFFF; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="padding: 32px 40px; text-align: center; border-bottom: 1px solid #E2E8F0;">
                            <div style="display: inline-block;">
                                <h1 style="margin: 0; font-size: 28px; font-weight: 700; color: #1F3C88; letter-spacing: -0.5px;">
                                    GrowithBI
                                </h1>
                                <p style="margin: 4px 0 0 0; font-size: 11px; color: #64748B; font-weight: 500; letter-spacing: 0.5px;">
                                    BANK INDONESIA • LAMPUNG
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px;">
                            <h1 style="margin: 0 0 24px 0; font-size: 24px; font-weight: 600; color: #0F172A; line-height: 1.3;">
                                Verifikasi Login Anda
                            </h1>
                            
                            <p style="margin: 0 0 24px 0; font-size: 16px; color: #475569; line-height: 1.6;">
                                Halo <strong style="color: #0F172A;">{{ $userName }}</strong>,
                            </p>

                            <p style="margin: 0 0 32px 0; font-size: 16px; color: #475569; line-height: 1.6;">
                                Kami menerima permintaan untuk masuk ke akun GrowithBI Anda. Gunakan kode verifikasi berikut untuk menyelesaikan proses login:
                            </p>

                            <!-- OTP Code Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 32px 0;">
                                <tr>
                                    <td style="background-color: #F8FAFC; border: 2px solid #1F3C88; border-radius: 8px; padding: 32px; text-align: center;">
                                        <div style="font-size: 36px; font-weight: 700; letter-spacing: 8px; color: #1F3C88; font-family: 'Courier New', monospace;">
                                            {{ $otpCode }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 32px 0; font-size: 14px; color: #DC2626; text-align: center; font-weight: 500;">
                                ⏱ Kode ini akan kadaluarsa dalam {{ $expiresInMinutes }} menit
                            </p>

                            <!-- Security Warning -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 32px 0;">
                                <tr>
                                    <td style="background-color: #FEF2F2; border-left: 4px solid #DC2626; padding: 20px; border-radius: 4px;">
                                        <h3 style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #DC2626;">
                                            ⚠️ Peringatan Keamanan
                                        </h3>
                                        <ul style="margin: 0; padding-left: 20px; font-size: 14px; color: #475569; line-height: 1.6;">
                                            <li style="margin-bottom: 8px;"><strong>Jangan bagikan</strong> kode ini kepada siapapun</li>
                                            <li style="margin-bottom: 8px;">Tim GrowithBI <strong>tidak akan pernah</strong> meminta kode verifikasi Anda</li>
                                            <li style="margin-bottom: 0;">Jika Anda tidak meminta kode ini, abaikan email ini dan segera ubah password Anda</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; font-size: 14px; color: #64748B; line-height: 1.6;">
                                Jika Anda mengalami kesulitan, silakan hubungi tim support kami melalui sistem GrowithBI.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 32px 40px; background-color: #F8FAFC; border-top: 1px solid #E2E8F0;">
                            <p style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #0F172A;">
                                Tim GrowithBI
                            </p>
                            <p style="margin: 0 0 16px 0; font-size: 13px; color: #64748B;">
                                Bank Indonesia Kantor Perwakilan Wilayah Lampung
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #94A3B8; line-height: 1.5;">
                                Email ini dikirim secara otomatis. Mohon tidak membalas email ini.<br>
                                © {{ date('Y') }} GrowithBI. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
