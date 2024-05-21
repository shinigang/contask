<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Models\Business;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BusinessController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Business::class, 'business');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = request()->input('query') ?? '';
        $businesses = Business::search($query)->paginate(10);
        return inertia('Businesses/Index', [
            'query' => $query,
            'businesses' => $businesses
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Businesses/Create', [
            'categories' => Category::all(),
            'tags' => Tag::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBusinessRequest $request)
    {
        $business = Business::create($request);
        if ($request->categories) {
            $business->categories()->sync($request->categories);
        }
        if ($request->tags) {
            $business->tags()->sync($request->tags);
        }

        return redirect()->route('business.index')->banner('Business was created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Business $business)
    {
        $taskStatuses = [];
        foreach (TaskStatus::cases() as $case) {
            $taskStatuses[$case->value] = $case->name;
        }

        return inertia('Businesses/Show', [
            'business' => $business,
            'taskStatuses' => $taskStatuses
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Business $business)
    {
        return inertia('Businesses/Edit', [
            'business' => $business,
            'categories' => Category::all(),
            'tags' => Tag::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBusinessRequest $request, Business $business)
    {
        $business->fill($request->all());
        $business->save();

        return redirect()->route('business.index')->banner('Business was updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Business $business)
    {
        $business->delete();

        return redirect()->route('business.index')->banner('Business was removed!');
    }
}
