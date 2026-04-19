<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

abstract class BaseStudentCrudController extends Controller
{
    protected $modelClass;
    protected $viewTitle;
    protected $routeBase;
    protected $formFields = [];

    public function __construct()
    {
        $this->middleware('student');
    }

    public function index()
    {
        $records = $this->newModel()->where('user_id', Auth::id())->latest()->paginate(20);

        return view('pages.student.modules.index', [
            'records' => $records,
            'title' => $this->viewTitle,
            'routeBase' => $this->routeBase,
        ]);
    }

    public function create()
    {
        return view('pages.student.modules.create', [
            'title' => $this->viewTitle,
            'routeBase' => $this->routeBase,
            'fields' => $this->formFields,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['user_id'] = Auth::id();

        $this->newModel()->create($data);

        return redirect()->route($this->routeBase . '.index')->with('flash_success', $this->viewTitle . ' created successfully.');
    }

    public function show($id)
    {
        return view('pages.student.modules.show', [
            'record' => $this->findOwnedRecord($id),
            'title' => $this->viewTitle,
            'routeBase' => $this->routeBase,
        ]);
    }

    public function edit($id)
    {
        return view('pages.student.modules.edit', [
            'record' => $this->findOwnedRecord($id),
            'title' => $this->viewTitle,
            'routeBase' => $this->routeBase,
            'fields' => $this->formFields,
        ]);
    }

    public function update(Request $request, $id)
    {
        $record = $this->findOwnedRecord($id);
        $data = $request->validate($this->rules());
        $record->update($data);

        return redirect()->route($this->routeBase . '.index')->with('flash_success', $this->viewTitle . ' updated successfully.');
    }

    public function destroy($id)
    {
        $this->findOwnedRecord($id)->delete();

        return redirect()->route($this->routeBase . '.index')->with('flash_success', $this->viewTitle . ' deleted successfully.');
    }

    abstract protected function rules(): array;

    protected function findOwnedRecord($id)
    {
        return $this->newModel()->where('user_id', Auth::id())->findOrFail($id);
    }

    protected function newModel(): Model
    {
        return new $this->modelClass;
    }
}
