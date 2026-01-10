<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pengajuan Magang - GrowithBI</title>
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
                                Informasi Pengajuan Magang Anda
                            </h1>
                            
                            <p style="margin: 0 0 32px 0; font-size: 16px; color: #475569; line-height: 1.6;">
                                Halo <strong style="color: #0F172A;">{{ $userName }}</strong>,
                            </p>

                            <p style="margin: 0 0 32px 0; font-size: 16px; color: #475569; line-height: 1.6;">
                                Kami ingin memberitahukan bahwa status pengajuan magang Anda di <strong>Bank Indonesia KPW Lampung</strong> telah diperbarui.
                            </p>

                            <!-- Status Box -->
                            @php
                                $borderColor = $status === 'approved' ? '#16A34A' : '#DC2626';
                                $statusColor = $status === 'approved' ? '#16A34A' : '#DC2626';
                                $tdStyle = "background-color: #F8FAFC; border: 2px solid {$borderColor}; border-radius: 8px; padding: 32px; text-align: center;";
                                $h2Style = "margin: 0 0 16px 0; font-size: 28px; font-weight: 700; color: {$statusColor};";
                            @endphp
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 32px 0;">
                                <tr>
                                    <td style="{{ $tdStyle }}">
                                        <p style="margin: 0 0 8px 0; font-size: 14px; color: #64748B; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">
                                            Status Pengajuan
                                        </p>
                                        <h2 style="{{ $h2Style }}">
                                            {{ $statusText }}
                                        </h2>
                                        <p style="margin: 0 0 8px 0; font-size: 15px; color: #475569;">
                                            <strong>Divisi:</strong> {{ $divisionName }}
                                        </p>
                                        <p style="margin: 0; font-size: 13px; color: #64748B;">
                                            Tanggal Update: {{ now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            @if($status === 'approved')
                            <!-- Approved Message -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 32px 0;">
                                <tr>
                                    <td style="background-color: #F0FDF4; border-left: 4px solid #16A34A; padding: 20px; border-radius: 4px;">
                                        <h3 style="margin: 0 0 12px 0; font-size: 18px; font-weight: 600; color: #16A34A;">
                                            🎉 Selamat!
                                        </h3>
                                        <p style="margin: 0 0 16px 0; font-size: 15px; color: #475569; line-height: 1.6;">
                                            Anda telah <strong>diterima</strong> sebagai peserta magang di Bank Indonesia KPW Lampung.
                                        </p>
                                        <p style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #0F172A;">
                                            Langkah Selanjutnya:
                                        </p>
                                        <ul style="margin: 0; padding-left: 20px; font-size: 14px; color: #475569; line-height: 1.8;">
                                            <li>Login ke sistem GrowithBI untuk melihat detail lebih lanjut</li>
                                            <li>Informasi selengkapnya akan dikirimkan melalui sistem</li>
                                            <li>Pantau terus dashboard Anda untuk update terbaru</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ url('/login') }}" style="display: inline-block; background-color: #16A34A; color: #FFFFFF; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 600; font-size: 16px;">
                                            Login ke Dashboard
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; font-size: 14px; color: #475569; line-height: 1.6;">
                                Kami sangat menantikan kehadiran Anda dan berharap pengalaman magang ini akan memberikan manfaat yang besar bagi pengembangan karir Anda.
                            </p>

                            @else
                            <!-- Rejected Message -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 32px 0;">
                                <tr>
                                    <td style="background-color: #FEF2F2; border-left: 4px solid #DC2626; padding: 20px; border-radius: 4px;">
                                        <h3 style="margin: 0 0 12px 0; font-size: 18px; font-weight: 600; color: #DC2626;">
                                            Terima Kasih Atas Partisipasi Anda
                                        </h3>
                                        <p style="margin: 0 0 16px 0; font-size: 15px; color: #475569; line-height: 1.6;">
                                            Terima kasih telah mendaftar sebagai peserta magang di Bank Indonesia KPW Lampung.
                                        </p>
                                        <p style="margin: 0 0 16px 0; font-size: 15px; color: #475569; line-height: 1.6;">
                                            Setelah melalui proses seleksi yang ketat, dengan berat hati kami informasikan bahwa pada kesempatan ini Anda belum dapat kami terima sebagai peserta magang.
                                        </p>
                                        <p style="margin: 0 0 12px 0; font-size: 14px; font-weight: 600; color: #0F172A;">
                                            Jangan Berkecil Hati!
                                        </p>
                                        <ul style="margin: 0; padding-left: 20px; font-size: 14px; color: #475569; line-height: 1.8;">
                                            <li>Tetap semangat dan terus tingkatkan kemampuan Anda</li>
                                            <li>Kesempatan lain mungkin akan datang di masa mendatang</li>
                                            <li>Kami menghargai minat dan dedikasi Anda</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ url('/divisions') }}" style="display: inline-block; background-color: #1F3C88; color: #FFFFFF; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 600; font-size: 16px;">
                                            Lihat Peluang Lainnya
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; font-size: 14px; color: #475569; line-height: 1.6;">
                                Kami mendoakan yang terbaik untuk perjalanan karir Anda ke depan.
                            </p>
                            @endif

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
                                Jika Anda memiliki pertanyaan, hubungi kami melalui sistem GrowithBI.<br>
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
