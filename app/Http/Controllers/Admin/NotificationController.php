<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index(Request $request): Response
    {
        // Sample notifications data
        // Dalam implementasi nyata, ini akan mengambil data dari database
        $notifications = collect([
            [
                'id' => 1,
                'title' => 'Aplikasi Baru',
                'message' => 'Ahmad Rizki Pratama telah mengirim aplikasi magang untuk divisi Human Capital Development',
                'type' => 'application',
                'data' => ['application_id' => 1],
                'read_at' => null,
                'created_at' => now()->subMinutes(30)->toISOString(),
            ],
            [
                'id' => 2,
                'title' => 'Laporan Harian Baru',
                'message' => 'Dewi Lestari telah mengirim laporan harian untuk tanggal ' . now()->format('d/m/Y'),
                'type' => 'logbook',
                'data' => ['logbook_id' => 5],
                'read_at' => null,
                'created_at' => now()->subHours(2)->toISOString(),
            ],
            [
                'id' => 3,
                'title' => 'Status Aplikasi Diperbarui',
                'message' => 'Status aplikasi magang telah diperbarui menjadi "Diterima"',
                'type' => 'status',
                'data' => ['application_id' => 3],
                'read_at' => now()->subHours(1)->toISOString(),
                'created_at' => now()->subDays(1)->toISOString(),
            ],
            [
                'id' => 4,
                'title' => 'Laporan Akhir Diterima',
                'message' => 'Muhammad Faisal telah mengirim laporan akhir magang',
                'type' => 'final_report',
                'data' => ['report_id' => 2],
                'read_at' => now()->subMinutes(45)->toISOString(),
                'created_at' => now()->subDays(2)->toISOString(),
            ],
            [
                'id' => 5,
                'title' => 'Aplikasi Menunggu Review',
                'message' => 'Sari Indah Permata telah mengirim aplikasi magang untuk divisi IT Support',
                'type' => 'application',
                'data' => ['application_id' => 8],
                'read_at' => null,
                'created_at' => now()->subDays(3)->toISOString(),
            ]
        ]);

        // Filter berdasarkan status read/unread jika ada parameter
        if ($request->has('filter')) {
            if ($request->filter === 'unread') {
                $notifications = $notifications->whereNull('read_at');
            } elseif ($request->filter === 'read') {
                $notifications = $notifications->whereNotNull('read_at');
            }
        }

        // Filter berdasarkan tipe jika ada parameter
        if ($request->has('type') && $request->type !== 'all') {
            $notifications = $notifications->where('type', $request->type);
        }

        // Sorting berdasarkan tanggal terbaru
        $notifications = $notifications->sortByDesc('created_at')->values();

        // Pagination sederhana (untuk demo)
        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $total = $notifications->count();
        $notifications = $notifications->forPage($currentPage, $perPage);

        $stats = [
            'total' => $total,
            'unread' => collect($notifications)->whereNull('read_at')->count(),
            'read' => collect($notifications)->whereNotNull('read_at')->count(),
        ];

        return Inertia::render('Admin/Notifications/Index', [
            'notifications' => $notifications->values(),
            'stats' => $stats,
            'filters' => [
                'filter' => $request->get('filter', 'all'),
                'type' => $request->get('type', 'all'),
            ],
            'pagination' => [
                'current_page' => $currentPage,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
            ]
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Request $request, $notificationId)
    {
        // Dalam implementasi nyata, ini akan update database
        // Notification::where('id', $notificationId)->update(['read_at' => now()]);
        
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi telah ditandai sebagai dibaca'
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        // Dalam implementasi nyata, ini akan update semua notifikasi yang belum dibaca
        // Notification::whereNull('read_at')->update(['read_at' => now()]);
        
        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi telah ditandai sebagai dibaca'
        ]);
    }

    /**
     * Delete notification.
     */
    public function destroy($notificationId)
    {
        // Dalam implementasi nyata, ini akan menghapus notifikasi dari database
        // Notification::destroy($notificationId);
        
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi telah dihapus'
        ]);
    }
}