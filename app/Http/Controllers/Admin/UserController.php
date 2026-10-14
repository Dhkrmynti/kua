<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Users/Index', [
            'staff' => User::whereIn('role', [Role::Admin, Role::Petugas])->orderBy('name')->get(['id', 'name', 'email', 'role', 'created_at']),
            'jumlahMasyarakat' => User::where('role', Role::Masyarakat)->count(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in([Role::Admin->value, Role::Petugas->value])],
            'password' => ['required', Password::min(8)],
        ]);

        User::create($data);

        return back()->with('success', 'Akun petugas berhasil dibuat.');
    }

    public function destroy(Request $request, User $user)
    {
        abort_if($user->id === $request->user()->id, 422, 'Tidak dapat menghapus akun sendiri.');
        abort_unless($user->isStaff(), 422, 'Hanya akun petugas/admin yang dapat dihapus di sini.');

        $user->delete();

        return back()->with('success', 'Akun berhasil dihapus.');
    }
}
