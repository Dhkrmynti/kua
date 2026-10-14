<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    $stats = [
        'total' => 0,
        'pending' => 0,
        'completed' => 0,
    ];

    if ($user) {
        if ($user->role === 'masyarakat') {
            $stats['total'] = \App\Models\Submission::where('user_id', $user->id)->count();
            $stats['pending'] = \App\Models\Submission::where('user_id', $user->id)->where('status', 'pending')->count();
            $stats['completed'] = \App\Models\Submission::where('user_id', $user->id)->where('status', 'completed')->count();
        } else {
            $stats['total'] = \App\Models\Submission::count();
            $stats['pending'] = \App\Models\Submission::where('status', 'pending')->count();
            $stats['completed'] = \App\Models\Submission::where('status', 'completed')->count();
        }
    }

    return Inertia::render('Dashboard', [
        'stats' => $stats
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('submissions', SubmissionController::class);

    // User Complaints
    Route::get('/complaints', [\App\Http\Controllers\ComplaintController::class, 'index'])->name('complaints.index');
    Route::post('/complaints', [\App\Http\Controllers\ComplaintController::class, 'store'])->name('complaints.store');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/submissions', [\App\Http\Controllers\Admin\SubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/{submission}', [\App\Http\Controllers\Admin\SubmissionController::class, 'show'])->name('submissions.show');
        Route::put('/submissions/{submission}/status', [\App\Http\Controllers\Admin\SubmissionController::class, 'updateStatus'])->name('submissions.status.update');

        // Admin Complaints
        Route::get('/complaints', [\App\Http\Controllers\Admin\ComplaintController::class, 'index'])->name('complaints.index');
        Route::put('/complaints/{complaint}', [\App\Http\Controllers\Admin\ComplaintController::class, 'update'])->name('complaints.update');
    });
});

require __DIR__.'/auth.php';
