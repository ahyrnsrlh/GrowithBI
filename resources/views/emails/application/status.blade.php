<x-mail::message>
# Informasi Pengajuan Magang Anda

Halo **{{ $userName }}**,

Kami ingin memberitahukan bahwa status pengajuan magang Anda di **Bank Indonesia KPW Lampung** telah diperbarui.

<x-mail::panel>
<div style="text-align: center; padding: 20px 0;">
@if($status === 'approved')
<h2 style="color: #059669; font-size: 24px; margin: 0;">
@else
<h2 style="color: #DC2626; font-size: 24px; margin: 0;">
@endif
Status: {{ $statusText }}
</h2>
<p style="color: #6B7280; margin-top: 10px;">
Divisi: {{ $divisionName }}
</p>
<p style="color: #6B7280; font-size: 14px;">
Tanggal Update: {{ now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
</p>
</div>
</x-mail::panel>

@if($status === 'approved')
## 🎉 Selamat!

Anda telah **diterima** sebagai peserta magang di Bank Indonesia KPW Lampung.

### Langkah Selanjutnya:
- Login ke sistem **GrowithBI** untuk melihat detail lebih lanjut
- Informasi selengkapnya akan dikirimkan melalui sistem
- Pantau terus dashboard Anda untuk update terbaru

Kami sangat menantikan kehadiran Anda dan berharap pengalaman magang ini akan memberikan manfaat yang besar bagi pengembangan karir Anda.

<x-mail::button :url="url('/login')" color="success">
Login ke Dashboard
</x-mail::button>

@else
## Terima Kasih Atas Partisipasi Anda

Terima kasih telah mendaftar sebagai peserta magang di Bank Indonesia KPW Lampung.

Setelah melalui proses seleksi yang ketat, dengan berat hati kami informasikan bahwa pada kesempatan ini Anda belum dapat kami terima sebagai peserta magang.

### Jangan Berkecil Hati!
- Tetap semangat dan terus tingkatkan kemampuan Anda
- Kesempatan lain mungkin akan datang di masa mendatang
- Kami menghargai minat dan dedikasi Anda

Kami mendoakan yang terbaik untuk perjalanan karir Anda ke depan.

<x-mail::button :url="url('/divisions')" color="primary">
Lihat Peluang Lainnya
</x-mail::button>

@endif

---

Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami melalui sistem GrowithBI atau email ini.

Hormat kami,<br>
**Tim GrowithBI**<br>
Bank Indonesia Kantor Perwakilan Wilayah Lampung

<small style="color: #9CA3AF; font-size: 12px;">
Email ini dikirim secara otomatis oleh sistem GrowithBI. Mohon tidak membalas email ini.
</small>

</x-mail::message>
