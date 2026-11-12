<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Requests\TimeTable\TSRequest;
use App\Http\Requests\TimeTable\TTRecordRequest;
use App\Http\Requests\TimeTable\TTRequest;
use App\Models\Course;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\TimeSlot;
use App\Models\TimeTable;
use App\Models\TimeTableRecord;
use App\Services\LmsAccess;
use App\Services\TimetableService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TimeTableController extends Controller
{
    public function __construct()
    {
        $this->middleware('departmentRole:coordinator')->except(['index', 'show_record', 'print_record']);
    }

    public function index()
    {
        $records = TimeTableRecord::with(['my_class', 'section'])->whereIn('my_class_id', LmsAccess::visibleClassIds())
            ->orderBy('name')->get()->filter(function ($record) { return $this->canRead($record); });
        $classes = MyClass::with('section')->whereIn('class_type_id', LmsAccess::departmentIds('coordinator'))->orderBy('name')->get();
        return view('pages.academic.timetables.index', compact('records', 'classes'));
    }

    public function manage($ttr)
    {
        $record = $this->editable($ttr);
        return $this->recordView($record, true);
    }

    public function show_record($ttr)
    {
        $record = TimeTableRecord::findOrFail($ttr);
        abort_unless($this->canRead($record), 403);
        return $this->recordView($record, false);
    }

    public function print_record($ttr)
    {
        return $this->show_record($ttr);
    }

    public function store_record(TTRecordRequest $request)
    {
        $data = $request->validated();
        $class = LmsAccess::assertClass($data['my_class_id'], 'coordinator');
        $this->assertSection($data['section_id'], $class->id);
        $data['year'] = Qs::getCurrentSession();
        $this->assertUniqueRecord($data);
        $record = TimeTableRecord::create($data);
        return redirect()->route('ttr.manage', $record->id)->with('flash_success', 'Timetable created. Add time slots and subject lessons.');
    }

    public function edit_record($ttr)
    {
        $record = $this->editable($ttr);
        $classes = MyClass::with('section')->whereIn('class_type_id', LmsAccess::departmentIds('coordinator'))->orderBy('name')->get();
        return view('pages.academic.timetables.edit', compact('record', 'classes'));
    }

    public function update_record(TTRecordRequest $request, $ttr)
    {
        $record = $this->editable($ttr);
        $data = $request->validated();
        LmsAccess::assertClass($data['my_class_id'], 'coordinator');
        $this->assertSection($data['section_id'], $data['my_class_id']);
        if (TimeTable::where('ttr_id', $record->id)->exists() &&
            ((int) $record->my_class_id !== (int) $data['my_class_id'] || (int) $record->section_id !== (int) $data['section_id'])) {
            throw ValidationException::withMessages(['my_class_id' => 'Remove the timetable lessons before changing its semester or section.']);
        }
        $data['year'] = $record->year;
        $this->assertUniqueRecord($data, $record->id);
        $record->update($data);
        return redirect()->route('tt.index')->with('flash_success', 'Timetable updated.');
    }

    public function delete_record($ttr)
    {
        $record = $this->editable($ttr);
        DB::transaction(function () use ($record) {
            TimeTable::where('ttr_id', $record->id)->delete();
            TimeSlot::where('ttr_id', $record->id)->delete();
            $record->delete();
        });
        return redirect()->route('tt.index')->with('flash_success', 'Timetable deleted.');
    }

    public function store(TTRequest $request)
    {
        $data = $request->validated();
        $this->editable($data['ttr_id']);
        DB::transaction(function () use ($data) {
            TimeTableRecord::whereKey($data['ttr_id'])->lockForUpdate()->first();
            TimeTable::create(app(TimetableService::class)->lessonData($data));
        });
        return redirect()->route('ttr.manage', $data['ttr_id'])->with('flash_success', 'Lesson scheduled.');
    }

    public function update(TTRequest $request, $tt)
    {
        $lesson = TimeTable::findOrFail($tt);
        $this->editable($lesson->ttr_id);
        $data = $request->validated();
        $this->editable($data['ttr_id']);
        abort_unless((int) $data['ttr_id'] === (int) $lesson->ttr_id, 422);
        DB::transaction(function () use ($lesson, $data) {
            TimeTableRecord::whereKey($data['ttr_id'])->lockForUpdate()->first();
            $lesson->update(app(TimetableService::class)->lessonData($data, $lesson->id));
        });
        return back()->with('flash_success', 'Lesson updated.');
    }

    public function delete($tt)
    {
        $lesson = TimeTable::findOrFail($tt);
        $this->editable($lesson->ttr_id);
        $lesson->delete();
        return back()->with('flash_success', 'Lesson deleted.');
    }

    public function store_time_slot(TSRequest $request)
    {
        $data = $request->validated();
        $this->editable($data['ttr_id']);
        TimeSlot::create($this->slotData($data));
        return redirect()->route('ttr.manage', $data['ttr_id'])->with('flash_success', 'Time slot added.');
    }

    public function edit_time_slot($ts)
    {
        $slot = TimeSlot::findOrFail($ts);
        $this->editable($slot->ttr_id);
        return redirect()->route('ttr.manage', $slot->ttr_id);
    }

    public function update_time_slot(TSRequest $request, $ts)
    {
        $slot = TimeSlot::findOrFail($ts);
        $this->editable($slot->ttr_id);
        abort_unless((int) $request->ttr_id === (int) $slot->ttr_id, 422);
        DB::transaction(function () use ($slot, $request) {
            $slot->update($this->slotData($request->validated()));
            foreach (TimeTable::where('ts_id', $slot->id)->get() as $lesson) {
                $lesson->update(app(TimetableService::class)->lessonData($lesson->toArray(), $lesson->id));
            }
        });
        return back()->with('flash_success', 'Time slot updated.');
    }

    public function delete_time_slot($ts)
    {
        $slot = TimeSlot::findOrFail($ts);
        $this->editable($slot->ttr_id);
        if (TimeTable::where('ts_id', $slot->id)->exists()) {
            throw ValidationException::withMessages(['ts_id' => 'Remove lessons from this slot before deleting it.']);
        }
        $slot->delete();
        return back()->with('flash_success', 'Time slot deleted.');
    }

    public function use_time_slot(Request $request, $ttr)
    {
        $target = $this->editable($ttr);
        $request->validate(['ttr_id' => 'required|integer|exists:time_table_records,id']);
        $source = $this->editable($request->ttr_id);
        abort_if($target->id === $source->id, 422);
        if (TimeTable::where('ttr_id', $target->id)->exists()) {
            throw ValidationException::withMessages(['ttr_id' => 'Remove current lessons before replacing time slots.']);
        }
        DB::transaction(function () use ($target, $source) {
            TimeSlot::where('ttr_id', $target->id)->delete();
            foreach (TimeSlot::where('ttr_id', $source->id)->get() as $slot) {
                $copy = $slot->replicate();
                $copy->ttr_id = $target->id;
                $copy->save();
            }
        });
        return back()->with('flash_success', 'Time slots copied.');
    }

    private function editable($id)
    {
        $record = TimeTableRecord::findOrFail($id);
        LmsAccess::assertClass($record->my_class_id, 'coordinator');
        return $record;
    }

    private function canRead($record)
    {
        if (!LmsAccess::visibleClassIds()->contains($record->my_class_id)) { return false; }
        if (Auth::user()->user_type === 'student') {
            $student = LmsAccess::studentRecord();
            return !$record->section_id || (int) $record->section_id === (int) $student->section_id;
        }
        return true;
    }

    private function assertSection($sectionId, $classId)
    {
        abort_unless(Section::where('my_class_id', $classId)->whereKey($sectionId)->exists(), 422, 'Select a section in this semester.');
    }

    private function assertUniqueRecord($data, $except = null)
    {
        $query = TimeTableRecord::where('my_class_id', $data['my_class_id'])->where('section_id', $data['section_id'])->where('year', $data['year'])->whereNull('exam_id');
        if ($except) { $query->where('id', '<>', $except); }
        if ($query->exists()) { throw ValidationException::withMessages(['section_id' => 'This section already has a timetable for the session.']); }
    }

    private function slotData($data)
    {
        $from = Carbon::createFromFormat('g:i A', $data['hour_from'].':'.$data['min_from'].' '.$data['meridian_from']);
        $to = Carbon::createFromFormat('g:i A', $data['hour_to'].':'.$data['min_to'].' '.$data['meridian_to']);
        if ($to->format('H:i') <= $from->format('H:i')) {
            throw ValidationException::withMessages(['hour_to' => 'The slot must end after it starts.']);
        }
        return array_merge($data, [
            'time_from' => $from->format('g:i A'), 'time_to' => $to->format('g:i A'),
            'timestamp_from' => strtotime('2000-01-01 '.$from->format('H:i')),
            'timestamp_to' => strtotime('2000-01-01 '.$to->format('H:i')),
            'full' => $from->format('g:i A').' - '.$to->format('g:i A'),
        ]);
    }

    private function recordView($record, $editable)
    {
        $lessons = TimeTable::with(['subject', 'time_slot'])->where('ttr_id', $record->id)->orderBy('day_num')->orderBy('timestamp_from')->get();
        $slots = TimeSlot::where('ttr_id', $record->id)->orderBy('timestamp_from')->get();
        $courses = Course::with('subject')->where('my_class_id', $record->my_class_id)->whereNotNull('subject_id')->get()
            ->filter(function ($course) use ($record) { return app(TimetableService::class)->teachers($record, $course->id)->isNotEmpty(); });
        return view('pages.academic.timetables.show', compact('record', 'lessons', 'slots', 'courses', 'editable'));
    }
}
