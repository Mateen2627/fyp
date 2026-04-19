<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    public function index()
    {
        return view('pages.admin.modules.reports.index', ['records' => AdminReport::latest()->paginate(20)]);
    }

    public function create()
    {
        return view('pages.admin.modules.reports.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'category' => 'nullable|string|max:100',
            'content' => 'required|string',
            'status' => 'required|string|in:draft,published,archived',
        ]);
        $data['created_by'] = Auth::id();

        AdminReport::create($data);
        return redirect()->route('admin.reports.index')->with('flash_success', 'Report created successfully.');
    }

    public function edit(AdminReport $report)
    {
        return view('pages.admin.modules.reports.edit', ['record' => $report]);
    }

    public function update(Request $request, AdminReport $report)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'category' => 'nullable|string|max:100',
            'content' => 'required|string',
            'status' => 'required|string|in:draft,published,archived',
        ]);

        $report->update($data);
        return redirect()->route('admin.reports.index')->with('flash_success', 'Report updated successfully.');
    }

    public function destroy(AdminReport $report)
    {
        $report->delete();
        return redirect()->route('admin.reports.index')->with('flash_success', 'Report deleted successfully.');
    }
}
