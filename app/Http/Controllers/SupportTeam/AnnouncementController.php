<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function __construct(){ $this->middleware('teamSAT'); }
    public function index(){ $records = Announcement::latest()->paginate(20); return view('pages.support_team.modules.index', ['records' => $records, 'module' => 'announcements', 'title' => 'Announcements']); }
    public function create(){ return view('pages.support_team.modules.create', ['module' => 'announcements', 'title' => 'Announcement']); }
    public function store(Request $request){ $data = $request->validate(['title' => 'required|string|max:200','body' => 'required|string','published_at' => 'nullable|date','is_active' => 'nullable|boolean']); $data['is_active'] = (bool) ($data['is_active'] ?? false); Announcement::create($data); return redirect()->route('announcements.index')->with('flash_success', 'Announcement created successfully.'); }
    public function show(Announcement $announcement){ return view('pages.support_team.modules.show', ['record' => $announcement, 'module' => 'announcements', 'title' => 'Announcement']); }
    public function edit(Announcement $announcement){ return view('pages.support_team.modules.edit', ['record' => $announcement, 'module' => 'announcements', 'title' => 'Announcement']); }
    public function update(Request $request, Announcement $announcement){ $data = $request->validate(['title' => 'required|string|max:200','body' => 'required|string','published_at' => 'nullable|date','is_active' => 'nullable|boolean']); $data['is_active'] = (bool) ($data['is_active'] ?? false); $announcement->update($data); return redirect()->route('announcements.index')->with('flash_success', 'Announcement updated successfully.'); }
    public function destroy(Announcement $announcement){ $announcement->delete(); return redirect()->route('announcements.index')->with('flash_success', 'Announcement deleted successfully.'); }
}
