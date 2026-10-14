<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class ProfilController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Profil', [
            'profil' => $request->user()->only('name', 'email', 'nik', 'telepon', 'alamat'),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'password_lama' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ], [], ['password_lama' => 'kata sandi lama', 'password' => 'kata sandi baru']);

        $user->fill(collect($data)->only('name', 'email', 'telepon', 'alamat')->all());

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
