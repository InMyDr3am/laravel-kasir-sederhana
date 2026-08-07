<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreSettingController extends Controller
{
    public function edit(): View
    {
        return view('settings.edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:100'],
            'store_address' => ['nullable', 'string', 'max:255'],
            'store_phone' => ['nullable', 'string', 'max:50'],
            'receipt_footer' => ['nullable', 'string', 'max:255'],
        ]);

        Setting::putMany($data);

        return redirect()->route('settings.edit')->with('status', 'Pengaturan toko berhasil disimpan.');
    }
}
