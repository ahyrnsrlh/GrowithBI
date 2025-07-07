<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Division;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicApplicationController extends Controller
{
    public function showApplicationForm()
    {
        $divisions = Division::where('is_active', true)->get();
        
        return Inertia::render('Public/ApplicationForm', [
            'divisions' => $divisions
        ]);
    }

    public function submitApplication(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:applications,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'university' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:14',
            'gpa' => 'required|numeric|min:0|max:4',
            'division_id' => 'required|exists:divisions,id',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'motivation' => 'required|string|max:1000',
            'cv' => 'required|file|mimes:pdf|max:5120', // 5MB max
            'transcript' => 'required|file|mimes:pdf|max:5120',
            'recommendation_letter' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Store uploaded files
        $cvPath = $request->file('cv')->store('applications/cv', 'public');
        $transcriptPath = $request->file('transcript')->store('applications/transcripts', 'public');
        $recommendationPath = null;
        
        if ($request->hasFile('recommendation_letter')) {
            $recommendationPath = $request->file('recommendation_letter')->store('applications/recommendations', 'public');
        }

        // Create application
        Application::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'university' => $request->university,
            'major' => $request->major,
            'semester' => $request->semester,
            'gpa' => $request->gpa,
            'division_id' => $request->division_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'motivation' => $request->motivation,
            'cv_path' => $cvPath,
            'transcript_path' => $transcriptPath,
            'recommendation_letter_path' => $recommendationPath,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        return Inertia::render('Public/ApplicationSuccess', [
            'message' => 'Your internship application has been submitted successfully! We will contact you soon.'
        ]);
    }

    public function checkApplicationStatus()
    {
        return Inertia::render('Public/CheckStatus');
    }

    public function getApplicationStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'application_id' => 'required|string'
        ]);

        $application = Application::where('email', $request->email)
            ->where('id', $request->application_id)
            ->with('division')
            ->first();

        if (!$application) {
            return back()->withErrors(['error' => 'Application not found. Please check your email and application ID.']);
        }

        return Inertia::render('Public/ApplicationStatus', [
            'application' => $application
        ]);
    }
}
