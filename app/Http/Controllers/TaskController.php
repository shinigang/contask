<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Business;
use App\Models\Person;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
        $query = request()->input('query') ?? '';
        $status = request()->input('status', 'all');
        $tasks = Task::search($query)
                    ->whereIn('status', $status != 'all' ? [$status] : Arr::pluck(TaskStatus::cases(), 'value'))
                    ->paginate(10)
                    ->appends(['status' => $status]);
        return inertia('Tasks/Index', [
            'query' => $query,
            'statuses' => $this->statuses,
            'tasks' => $tasks,
            'status' => $status
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Tasks/Create', [
            'statuses' => $this->statuses,
            'businesses' => Business::orderBy('name')->get(),
            'people' => Person::orderBy('last_name')->get(),
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
            $model::find($request->owner_id)->tasks()->create($request->all());

            if ($request->new) {
                return redirect()->back()->banner('Task was created!');
            }
            else {
                return redirect()->route('task.index')->banner('Task was created!');
            }
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
            'businesses' => Business::orderBy('name')->get(),
            'people' => Person::orderBy('last_name')->get(),
            'ownerTypes' => ['person', 'business']
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
     * Update the status of the specified resource in storage.
     */
    public function updateStatus(Request $request, Task $task)
    {
        $task->status = $request->status;
        $task->save();

        return redirect()->route('task.index')->banner('Task status was updated!');
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
