<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Models\Business;
use App\Models\Person;
use App\Models\Tag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PersonController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Person::class, 'person');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = request()->input('query') ?? '';
        $people = Person::search($query)->paginate(10);
        return inertia('People/Index', [
            'query' => $query,
            'people' => $people
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('People/Create', [
            'businesses' => Business::all(),
            'tags' => Tag::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonRequest $request)
    {
        $person = Person::create($request);
        if ($request->tags) {
            $person->tags()->sync($request->tags);
        }

        return redirect()->route('person.index')->banner('Person was created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        $taskStatuses = [];
        foreach (TaskStatus::cases() as $case) {
            $taskStatuses[$case->value] = $case->name;
        }

        return inertia('People/Show', [
            'person' => $person,
            'taskStatuses' => $taskStatuses
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $person)
    {
        return inertia('People/Edit', [
            'person' => $person,
            'businesses' => Business::all(),
            'tags' => Tag::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonRequest $request, Person $person)
    {
        $person->fill($request->all());
        $person->save();
        if ($request->tags) {
            $person->tags()->sync($request->tags);
        }

        return redirect()->route('person.index')->banner('Person was updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        $person->delete();

        return redirect()->route('person.index')->banner('Person was removed!');
    }
}
