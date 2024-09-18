<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $request->validate([
            'per_page' => ['nullable', 'numeric', 'min:1', 'max:100'],
            'page' => ['nullable', 'numeric', 'min:1'],
            'search' => ['nullable', 'string'],
        ]);

        $roles = Role::where('name', 'like', '%' . $request->search . '%')
            ->orWhere('title', 'like', '%' . $request->search . '%')
            ->paginate(request('per_page', 15))
            ->withQueryString();

        return Inertia::render('Role/Index', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Role::create([
            'name' => $request->name,
            'title' => $request->title,
            'description' => $request->description,
            'guard_name' => 'web',
        ]);

        return back()->with('success', trans('Successfully created.'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $role->update([
            'name' => $request->name,
            'title' => $request->title,
            'description' => $request->description,
            'guard_name' => 'web',
        ]);

        return back()->with('success', trans('Successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return back()->with('success', trans('Successfully deleted.'));
    }
}
