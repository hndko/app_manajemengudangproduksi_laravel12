<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->groupBy('group');
        return view('backend.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'company_logo' => 'nullable|image|max:2048',
        ]);

        foreach ($request->settings as $key => $value) {
            $setting = SiteSetting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => $value]);
            }
        }

        if ($request->hasFile('company_logo')) {
            $path = $request->file('company_logo')->store('settings', 'public');
            SiteSetting::set('company_logo', $path, 'image', 'company');
        }

        ActivityLog::log('update', 'Mengubah pengaturan website');

        return back()->with('success', 'Pengaturan berhasil disimpan');
    }
}
