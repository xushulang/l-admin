<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\Phone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
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

        $users = User::where('name', 'like', '%' . $request->search . '%')
            ->orWhere('username', 'like', '%' . $request->search . '%')
            ->orWhere('phone', 'like', '%' . $request->search . '%')
            ->orWhere('email', 'like', '%' . $request->search . '%')
            ->paginate(request('per_page', 15))
            ->withQueryString();

        return Inertia::render('User/Index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:255', 'unique:users', new Phone],
            'email' => ['nullable', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'max:255', Password::defaults()],
        ]);

        User::create($validated);

        return back()->with('success', trans('Successfully created.'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user)],
            'phone' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user), new Phone],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => ['nullable', 'string', 'max:255', Password::defaults()],
        ]);

        $user->update($validated);

        return back()->with('success', trans('Successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return back()->with('success', trans('Successfully deleted.'));
    }
}
