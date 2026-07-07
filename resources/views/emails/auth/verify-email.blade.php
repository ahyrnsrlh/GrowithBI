<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun GrowithBI</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif; background-color: #F8FAFC; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8FAFC;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #FFFFFF; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);">
                    
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
                            <h2 style="margin: 0 0 16px 0; font-size: 22px; font-weight: 600; color: #0F172A; line-height: 1.3;">
                                Selamat Datang di GrowithBI! 🎉
                            </h2>
                            
                            <p style="margin: 0 0 24px 0; font-size: 15px; color: #475569; line-height: 1.6;">
                                Halo <strong style="color: #0F172A;">{{ $userName }}</strong>,
                            </p>

                            <p style="margin: 0 0 24px 0; font-size: 15px; color: #475569; line-height: 1.6;">
                                Terima kasih telah mendaftar di sistem GrowithBI Bank Indonesia Kantor Perwakilan Wilayah Lampung. Sebelum Anda dapat masuk dan menggunakan fitur absensi online serta manajemen profil, silakan lakukan verifikasi alamat email Anda terlebih dahulu.
                            </p>

                            <!-- Verification Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 32px 0;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ $actionUrl }}" target="_blank" style="display: inline-block; background: linear-gradient(135deg, #1F3C88 0%, #2F4C9E 100%); color: #FFFFFF; font-size: 15px; font-weight: 600; text-decoration: none; padding: 14px 36px; border-radius: 8px; box-shadow: 0 4px 10px rgba(31, 60, 136, 0.2); transition: all 0.2s ease;">
                                            Verifikasi Email Saya
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 32px 0; font-size: 13px; color: #64748B; text-align: center; font-weight: 500; font-style: italic;">
                                ⏱ Link verifikasi ini akan kadaluarsa dalam {{ $expiresInMinutes }} menit.
                            </p>

                            <!-- Notice / Alternative Link -->
                            <p style="margin: 0 0 24px 0; font-size: 13px; color: #64748B; line-height: 1.6; word-break: break-all;">
                                Jika tombol di atas tidak berfungsi, Anda juga dapat menyalin dan menempelkan tautan berikut ke browser Anda:<br>
                                <a href="{{ $actionUrl }}" style="color: #1F3C88; text-decoration: underline;">{{ $actionUrl }}</a>
                            </p>

                            <!-- Security Warning -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 32px 0 0 0;">
                                <tr>
                                    <td style="background-color: #F8FAFC; border-left: 4px solid #1F3C88; padding: 18px; border-radius: 4px;">
                                        <h4 style="margin: 0 0 8px 0; font-size: 13px; font-weight: 600; color: #1F3C88;">
                                            Hubungi Bantuan
                                        </h4>
                                        <p style="margin: 0; font-size: 13px; color: #475569; line-height: 1.5;">
                                            Jika Anda merasa tidak melakukan pendaftaran ini, abaikan email ini. Untuk bantuan teknis lebih lanjut, silakan hubungi tim support GrowithBI.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 32px 40px; background-color: #F8FAFC; border-top: 1px solid #E2E8F0; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                            <p style="margin: 0 0 6px 0; font-size: 14px; font-weight: 600; color: #0F172A;">
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
