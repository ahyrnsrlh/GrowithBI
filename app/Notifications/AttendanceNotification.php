<?php

namespace App\Notifications;

use App\Models\Attendance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class AttendanceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $attendance;
    public $type; // 'checked_in', 'checked_out', 'late', 'early_checkout', 'location_warning', 'face_registered', 'reminder_checkin', 'reminder_checkout'

    /**
     * Create a new notification instance.
     */
    public function __construct(Attendance $attendance, string $type)
    {
        $this->attendance = $attendance;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     * SPECIAL: NO EMAIL untuk attendance notifications (hanya database + broadcast)
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Database + Broadcast ONLY (NO MAIL per user request)
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'title' => $this->getTitle(),
            'message' => $this->getMessage($notifiable),
            'attendance_id' => $this->attendance->id,
            'date' => $this->attendance->date,            'user_name' => $this->attendance->user->name,            'check_in_time' => $this->attendance->check_in_time,
            'check_out_time' => $this->attendance->check_out_time,
            'url' => '/peserta/attendance',
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => $this->type,
            'title' => $this->getTitle(),
            'message' => $this->getMessage($notifiable),
            'attendance_id' => $this->attendance->id,
            'date' => $this->attendance->date,            'user_name' => $this->attendance->user->name,            'check_in_time' => $this->attendance->check_in_time,
            'check_out_time' => $this->attendance->check_out_time,
            'url' => '/peserta/attendance',
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
            'created_at' => now()->toISOString(),
        ]);
    }

    /**
     * Get notification title based on type
     */
    private function getTitle(): string
    {
        return match($this->type) {
            'checked_in' => 'Check-in Berhasil',
            'checked_out' => 'Check-out Berhasil',
            'late' => 'Terlambat Check-in',
            'early_checkout' => 'Check-out Lebih Awal',
            'location_warning' => 'Peringatan Lokasi',
            'location_invalid' => 'Lokasi Tidak Valid',
            'face_registered' => 'Wajah Terdaftar',
            'face_not_recognized' => 'Verifikasi Wajah Gagal',
            'reminder_checkin' => 'Reminder Check-in',
            'reminder_checkout' => 'Reminder Check-out',
            'missing_checkin' => 'Belum Check-in',
            'missing_checkout' => 'Belum Check-out',
            default => 'Notifikasi Absensi',
        };
    }

    /**
     * Get notification message based on type
     * Berbeda untuk admin (menampilkan nama user) vs user (menampilkan "Anda")
     */
    private function getMessage(object $notifiable): string
    {
        $userName = $this->attendance->user->name;
        $isAdmin = $notifiable->role === 'admin';
        
        return match($this->type) {
            'checked_in' => $isAdmin 
                ? "{$userName} telah check-in pada {$this->attendance->check_in_time}"
                : "Anda telah berhasil check-in pada {$this->attendance->check_in_time}",
            'checked_out' => $isAdmin
                ? "{$userName} telah check-out pada {$this->attendance->check_out_time}"
                : "Anda telah berhasil check-out pada {$this->attendance->check_out_time}",
            'late' => $isAdmin
                ? "{$userName} terlambat check-in. Waktu: {$this->attendance->check_in_time}"
                : "Anda terlambat melakukan check-in. Waktu check-in: {$this->attendance->check_in_time}",
            'early_checkout' => $isAdmin
                ? "{$userName} melakukan check-out lebih awal dari jadwal"
                : "Anda melakukan check-out lebih awal dari jadwal.",
            'location_warning' => $isAdmin
                ? "Lokasi check-in {$userName} berbeda dari lokasi yang ditetapkan"
                : "Lokasi check-in Anda berbeda dari lokasi yang ditetapkan.",
            'location_invalid' => $isAdmin
                ? "Check-in {$userName} gagal: lokasi di luar radius yang diizinkan"
                : "Check-in gagal: Anda berada di luar lokasi yang diizinkan.",
            'face_registered' => $isAdmin
                ? "Wajah {$userName} telah berhasil didaftarkan untuk verifikasi absensi"
                : "Wajah Anda telah berhasil didaftarkan untuk verifikasi absensi.",
            'face_not_recognized' => $isAdmin
                ? "Verifikasi wajah {$userName} gagal saat check-in"
                : "Verifikasi wajah gagal. Pastikan wajah Anda terlihat jelas di kamera.",
            'reminder_checkin' => $isAdmin
                ? "{$userName} belum melakukan check-in hari ini"
                : "Jangan lupa untuk melakukan check-in hari ini.",
            'reminder_checkout' => $isAdmin
                ? "{$userName} belum melakukan check-out hari ini"
                : "Jangan lupa untuk melakukan check-out hari ini.",
            'missing_checkin' => $isAdmin
                ? "{$userName} belum melakukan check-in hari ini"
                : "Anda belum melakukan check-in hari ini.",
            'missing_checkout' => $isAdmin
                ? "{$userName} belum melakukan check-out hari ini"
                : "Anda belum melakukan check-out hari ini.",
            default => $isAdmin
                ? "Absensi {$userName} telah diperbarui"
                : "Absensi Anda telah diperbarui.",
        };
    }

    /**
     * Get icon based on type
     */
    private function getIcon(): string
    {
        return match($this->type) {
            'checked_in' => 'arrow-right-on-rectangle',
            'checked_out' => 'arrow-left-on-rectangle',
            'late' => 'exclamation-triangle',
            'early_checkout' => 'exclamation-circle',
            'location_warning' => 'map-pin',
            'location_invalid' => 'x-circle',
            'face_registered' => 'user-circle',
            'face_not_recognized' => 'face-frown',
            'reminder_checkin' => 'bell-alert',
            'reminder_checkout' => 'bell-alert',
            'missing_checkin' => 'clock',
            'missing_checkout' => 'clock',
            default => 'calendar',
        };
    }

    /**
     * Get color based on type
     */
    private function getColor(): string
    {
        return match($this->type) {
            'checked_in' => 'green',
            'checked_out' => 'blue',
            'late' => 'red',
            'early_checkout' => 'orange',
            'location_warning' => 'yellow',
            'location_invalid' => 'red',
            'face_registered' => 'purple',
            'face_not_recognized' => 'red',
            'reminder_checkin' => 'indigo',
            'reminder_checkout' => 'indigo',
            'missing_checkin' => 'amber',
            'missing_checkout' => 'amber',
            default => 'gray',
        };
    }
}
