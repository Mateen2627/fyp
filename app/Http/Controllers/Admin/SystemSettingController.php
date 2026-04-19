<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    public function index()
    {
        return view('pages.admin.modules.settings.index', ['records' => Setting::orderBy('type')->paginate(30)]);
    }

    public function create()
    {
        return view('pages.admin.modules.settings.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string|max:200|unique:settings,type',
            'description' => 'nullable|string',
        ]);

        Setting::create($data);
        return redirect()->route('admin.system-settings.index')->with('flash_success', 'Setting created successfully.');
    }

    public function edit(Setting $system_setting)
    {
        return view('pages.admin.modules.settings.edit', ['record' => $system_setting]);
    }

    public function update(Request $request, Setting $system_setting)
    {
        $data = $request->validate([
            'type' => 'required|string|max:200|unique:settings,type,'.$system_setting->id,
            'description' => 'nullable|string',
        ]);

        $system_setting->update($data);
        return redirect()->route('admin.system-settings.index')->with('flash_success', 'Setting updated successfully.');
    }

    public function destroy(Setting $system_setting)
    {
        $system_setting->delete();
        return redirect()->route('admin.system-settings.index')->with('flash_success', 'Setting deleted successfully.');
    }
}
