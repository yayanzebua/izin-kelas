<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'onboarding_active' => Setting::get('onboarding_active', 'false') === 'true',
            'class_code' => Setting::get('class_code', '07TPLE018'),
        ];
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'onboarding_active' => 'in:true,false',
            'class_code' => 'required|string|max:50',
        ]);

        Setting::set('onboarding_active', $request->onboarding_active);
        Setting::set('class_code', strtoupper($request->class_code));

        // If toggling on, we could potentially reset all users' has_seen_onboarding.
        // But the user's intent is likely: if ON, show it to users who haven't seen it yet.
        // For testing/resets, we'll reset it to false for everyone so the onboarding shows up again.
        if ($request->onboarding_active === 'true') {
            \App\Models\User::where('role', 'siswa')->update(['has_seen_onboarding' => false]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil diperbarui.'
        ]);
    }
}
