<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogbookController extends Controller
{
    public function index()
    {
        $logbooks = Logbook::with(['user:id,name,email'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('Admin/Logbooks/Index', [
            'logbooks' => $logbooks
        ]);
    }

    public function show(Logbook $logbook)
    {
        $logbook->load(['user:id,name,email']);

        return Inertia::render('Admin/Logbooks/Show', [
            'logbook' => $logbook
        ]);
    }

    public function approve(Logbook $logbook)
    {
        $logbook->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'Logbook berhasil disetujui.');
    }
}
