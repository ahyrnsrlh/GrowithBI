<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PembimbingController extends Controller
{
    public function dashboard()
    {
        $supervisedInterns = User::where('role', 'peserta')
            ->where('supervisor_id', Auth::id())
            ->where('is_active', true)
            ->with('logbooks')
            ->get();

        $recentLogbooks = Logbook::whereHas('user', function($query) {
                $query->where('supervisor_id', Auth::id());
            })
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        return Inertia::render('Pembimbing/Dashboard', [
            'supervisedInterns' => $supervisedInterns,
            'recentLogbooks' => $recentLogbooks
        ]);
    }

    public function myInterns()
    {
        $interns = User::where('role', 'peserta')
            ->where('supervisor_id', Auth::id())
            ->with(['logbooks' => function($query) {
                $query->latest()->take(5);
            }])
            ->paginate(10);

        return Inertia::render('Pembimbing/Interns', [
            'interns' => $interns
        ]);
    }

    public function reviewLogbook(Request $request, $id)
    {
        $logbook = Logbook::whereHas('user', function($query) {
                $query->where('supervisor_id', Auth::id());
            })
            ->findOrFail($id);

        $request->validate([
            'feedback' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $logbook->update([
            'supervisor_feedback' => $request->feedback,
            'supervisor_rating' => $request->rating,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id()
        ]);

        return back()->with('message', 'Logbook reviewed successfully.');
    }

    public function logbookHistory($internId)
    {
        $intern = User::where('role', 'peserta')
            ->where('supervisor_id', Auth::id())
            ->findOrFail($internId);

        $logbooks = Logbook::where('user_id', $internId)
            ->with('user')
            ->latest()
            ->paginate(15);

        return Inertia::render('Pembimbing/LogbookHistory', [
            'intern' => $intern,
            'logbooks' => $logbooks
        ]);
    }
}
