<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSettingController extends Controller
{
    public function index()
    {
        $setting = SchoolSetting::firstOrCreate([], [
            'name' => 'myLearn LMS'
        ]);
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $setting = SchoolSetting::first();
        $setting->name = $request->name;

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $setting->logo = $request->file('logo')->store('school', 'public');
        }

        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan sekolah berhasil diperbarui.');
    }
}
