<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubmissionController extends Controller
{
    public function index()
    {
        // Must be admin
        if (auth()->user()->role === 'masyarakat') {
            abort(403);
        }

        $submissions = Submission::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/Submissions/Index', [
            'submissions' => $submissions
        ]);
    }

    public function show(Submission $submission)
    {
        if (auth()->user()->role === 'masyarakat') {
            abort(403);
        }

        $submission->load('documents', 'user');

        return Inertia::render('Admin/Submissions/Show', [
            'submission' => $submission
        ]);
    }

    public function updateStatus(Request $request, Submission $submission)
    {
        if (auth()->user()->role === 'masyarakat') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,verified,scheduled,completed,rejected',
            'admin_notes' => 'nullable|string'
        ]);

        $submission->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        return redirect()->back()->with('message', 'Status pengajuan berhasil diperbarui.');
    }
}
