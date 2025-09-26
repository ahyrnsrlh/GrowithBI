<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Logbook;
use App\Models\User;
use App\Models\Division;

try {
    $logbooks = Logbook::with(['user:id,name', 'division:id,name'])
        ->orderBy('created_at', 'desc')
        ->get();

    echo "=== DAFTAR LOGBOOK YANG SUDAH DIBUAT ===\n\n";
    
    if ($logbooks->count() > 0) {
        foreach ($logbooks as $index => $logbook) {
            echo ($index + 1) . ". ID: {$logbook->id}\n";
            echo "   Judul: {$logbook->title}\n";
            echo "   User: " . ($logbook->user ? $logbook->user->name : 'N/A') . "\n";
            echo "   Divisi: " . ($logbook->division ? $logbook->division->name : 'N/A') . "\n";
            echo "   Tanggal: {$logbook->date}\n";
            echo "   Durasi: {$logbook->duration} jam\n";
            echo "   Status: {$logbook->status}\n";
            echo "   Aktivitas: " . substr($logbook->activities, 0, 100) . "...\n";
            echo "   Dibuat: {$logbook->created_at}\n";
            echo "   ----------------------------------------\n\n";
        }
        
        echo "\nTotal logbook: " . $logbooks->count() . "\n";
        
        // Statistik berdasarkan status
        $statuses = $logbooks->groupBy('status');
        echo "\n=== STATISTIK BERDASARKAN STATUS ===\n";
        foreach ($statuses as $status => $items) {
            echo "- {$status}: " . $items->count() . " logbook\n";
        }
    } else {
        echo "Belum ada logbook yang dibuat.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>