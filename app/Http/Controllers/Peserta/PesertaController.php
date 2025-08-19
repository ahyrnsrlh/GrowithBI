<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PesertaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $recentLogbooks = $user->logbooks()
            ->latest()
            ->take(5)
            ->get();

        $totalLogbooks = $user->logbooks()->count();

        return Inertia::render('Peserta/Dashboard', [
            'recentLogbooks' => $recentLogbooks,
            'totalLogbooks' => $totalLogbooks,
            'supervisor' => $user->supervisor
        ]);
    }

    public function logbooks()
    {
        $user = Auth::user();
        $logbooks = $user->logbooks()
            ->latest()
            ->paginate(15);

        return Inertia::render('Peserta/Logbooks', [
            'logbooks' => $logbooks
        ]);
    }

    public function createLogbook()
    {
        return Inertia::render('Peserta/CreateLogbook');
    }

    public function storeLogbook(Request $request)
    {
        $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'activity' => 'required|string|max:1000',
            'description' => 'required|string|max:2000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240' // 10MB max per file
        ]);

        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachmentPaths[] = $file->store('logbook-attachments', 'public');
            }
        }

        Logbook::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'activity' => $request->activity,
            'description' => $request->description,
            'attachments' => $attachmentPaths,
            'status' => 'pending'
        ]);

        return redirect()->route('peserta.logbooks')->with('message', 'Logbook entry created successfully.');
    }

    public function editLogbook($id)
    {
        $logbook = Logbook::where('user_id', Auth::id())
            ->where('status', 'pending') // Only allow editing pending logbooks
            ->findOrFail($id);

        return Inertia::render('Peserta/EditLogbook', [
            'logbook' => $logbook
        ]);
    }

    public function updateLogbook(Request $request, $id)
    {
        $logbook = Logbook::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'activity' => 'required|string|max:1000',
            'description' => 'required|string|max:2000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240'
        ]);

        $attachmentPaths = $logbook->attachments ?? [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachmentPaths[] = $file->store('logbook-attachments', 'public');
            }
        }

        $logbook->update([
            'date' => $request->date,
            'activity' => $request->activity,
            'description' => $request->description,
            'attachments' => $attachmentPaths,
        ]);

        return redirect()->route('peserta.logbooks')->with('message', 'Logbook entry updated successfully.');
    }

    public function profile()
    {
        return Inertia::render('Peserta/Profile', [
            'user' => Auth::user()->load('supervisor')
        ]);
    }
}
