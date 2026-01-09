<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - GrowithBI</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif; background-color: #F8FAFC; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #FFFFFF; border-radius: 8px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="padding: 32px 40px; text-align: center; border-bottom: 1px solid #E2E8F0;">
                            <img src="{{ asset('storage/logo_web2.png') }}" alt="GrowithBI Logo" style="height: 40px; width: auto; display: inline-block;">
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px;">
                            <h1 style="margin: 0 0 24px 0; font-size: 24px; font-weight: 600; color: #0F172A; line-height: 1.3;">
                                Reset Password Anda
                            </h1>
                            
                            <p style="margin: 0 0 24px 0; font-size: 16px; color: #475569; line-height: 1.6;">
                                Halo,
                            </p>

                            <p style="margin: 0 0 32px 0; font-size: 16px; color: #475569; line-height: 1.6;">
                                Kami menerima permintaan untuk mereset password akun GrowithBI Anda. Klik tombol di bawah ini untuk membuat password baru:
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 32px 0;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ $actionUrl }}" style="display: inline-block; background-color: #1F3C88; color: #FFFFFF; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 600; font-size: 16px;">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Alternative Link -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 32px 0;">
                                <tr>
                                    <td style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 20px;">
                                        <p style="margin: 0 0 12px 0; font-size: 13px; color: #64748B; font-weight: 500;">
                                            Jika tombol tidak berfungsi, salin dan tempel URL berikut ke browser:
                                        </p>
                                        <p style="margin: 0; font-size: 12px; color: #1F3C88; word-break: break-all; font-family: 'Courier New', monospace;">
                                            {{ $actionUrl }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Expiration Notice -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 32px 0;">
                                <tr>
                                    <td style="background-color: #FEF9E7; border-left: 4px solid #F59E0B; padding: 16px; border-radius: 4px;">
                                        <p style="margin: 0; font-size: 14px; color: #92400E;">
                                            <strong>⏱ Link ini akan kadaluarsa dalam {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} menit</strong> untuk menjaga keamanan akun Anda.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Warning -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
                                <tr>
                                    <td style="background-color: #FEF2F2; border-left: 4px solid #DC2626; padding: 20px; border-radius: 4px;">
                                        <h3 style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #DC2626;">
                                            ⚠️ Peringatan Keamanan
                                        </h3>
                                        <p style="margin: 0 0 8px 0; font-size: 14px; color: #475569; line-height: 1.6;">
                                            Jika Anda <strong>tidak meminta</strong> reset password, abaikan email ini. Password Anda tidak akan berubah dan akun Anda tetap aman.
                                        </p>
                                        <p style="margin: 0; font-size: 14px; color: #475569; line-height: 1.6;">
                                            Jika Anda merasa ada aktivitas mencurigakan, segera hubungi tim support kami.
                                        </p>
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
