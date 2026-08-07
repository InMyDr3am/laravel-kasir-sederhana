<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function edit(): View
    {
        return view('account.edit');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'current_password.current_password' => 'Password saat ini salah.',
        ]);

        $request->user()->update([
            'password' => $request->input('password'),
        ]);

        return redirect()->route('account.edit')->with('status', 'Password berhasil diperbarui.');
    }
}
