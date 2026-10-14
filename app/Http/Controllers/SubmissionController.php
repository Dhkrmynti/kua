<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        // Get submissions for the logged in user
        $submissions = Submission::where('user_id', $request->user()->id)
            ->with('documents')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Submissions/Index', [
            'submissions' => $submissions
        ]);
    }

    public function create()
    {
        return Inertia::render('Submissions/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_type' => 'required|in:nikah,talak,cerai,rujuk',
            'form_data' => 'required|array',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:2048' // max 2MB
        ]);

        $submission = Submission::create([
            'user_id' => $request->user()->id,
            'service_type' => $request->service_type,
            'status' => 'pending',
            'form_data' => $request->form_data,
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $type => $file) {
                $path = $file->store('documents', 'public');
                $submission->documents()->create([
                    'document_type' => $type,
                    'file_path' => $path
                ]);
            }
        }

        return redirect()->route('submissions.index')
            ->with('message', 'Pengajuan berhasil dikirim dan sedang menunggu verifikasi.');
    }

    public function show(Submission $submission)
    {
        // Check authorization
        if (auth()->user()->role === 'masyarakat' && $submission->user_id !== auth()->id()) {
            abort(403);
        }

        $submission->load('documents', 'user');

        return Inertia::render('Submissions/Show', [
            'submission' => $submission
        ]);
    }
}
