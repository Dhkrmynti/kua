<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/Complaints/Index', [
            'complaints' => $complaints
        ]);
    }

    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'admin_reply' => 'required|string',
            'status' => 'required|in:open,closed',
        ]);

        $complaint->update([
            'admin_reply' => $validated['admin_reply'],
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', 'Tanggapan berhasil dikirim.');
    }
}
