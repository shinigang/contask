<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Business;
use App\Models\Person;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    private $statuses = [];

    public function __construct()
    {
        $this->statuses = [];
        foreach (TaskStatus::cases() as $case) {
            $this->statuses[$case->value] = $case->name;
        }

        $this->authorizeResource(Task::class, 'task');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Tasks/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Tasks/Create', [
            'statuses' => $this->statuses,
            'businesses' => Business::all(),
            'people' => Person::all(),
            'ownerTypes' => ['person', 'business']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $model = null;
        switch ($request->owner_type) {
            case 'person': $model = Person::class; break;
            case 'business': $model = Business::class; break;
        }

        if ($model) {
            $model::find($request->owner_id)->tasks()->create($request);

            return redirect()->route('task.index')->banner('Task was created!');
        }

        return abort(400, 'Invalid task owner');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return inertia('Tasks/Edit', [
            'task' => $task,
            'statuses' => $this->statuses,
            'businesses' => Business::all(),
            'people' => Person::all(),
            'owner_types' => ['person', 'business']
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $owner_type = Person::class;
        if ($request->owner_type == 'business') {
            $owner_type = Business::class;
        }

        $task->fill($request->all());
        $task->taskable_id = $request->owner_id;
        $task->taskable_type = $owner_type;
        $task->save();

        return redirect()->route('task.index')->banner('Task was updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('task.index')->banner('Task was removed!');
    }
}
