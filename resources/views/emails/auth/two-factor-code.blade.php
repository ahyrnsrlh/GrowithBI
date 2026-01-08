@component('mail::message')
# 🔐 Kode Verifikasi Login

Halo **{{ $userName }}**,

Kami menerima permintaan untuk masuk ke akun GrowithBI Anda. Gunakan kode verifikasi berikut untuk menyelesaikan proses login:

@component('mail::panel')
<div style="text-align: center;">
<span style="font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #1e40af; font-family: monospace;">{{ $otpCode }}</span>
</div>
@endcomponent

**Kode ini akan kadaluarsa dalam {{ $expiresInMinutes }} menit.**

---

## ⚠️ Peringatan Keamanan

- **Jangan bagikan** kode ini kepada siapapun
- Tim GrowithBI **tidak akan pernah** meminta kode verifikasi Anda
- Jika Anda tidak meminta kode ini, abaikan email ini dan segera [ubah password]({{ route('password.request') }}) Anda

---

Jika Anda mengalami kesulitan, silakan hubungi tim support kami.

Salam,<br>
**Tim GrowithBI**<br>
Bank Indonesia KPW Lampung

@component('mail::subcopy')
Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
@endcomponent
@endcomponent
