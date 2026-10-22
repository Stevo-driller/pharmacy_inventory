<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display all users.
     */
    public function index()
    {
        $users = User::latest()->get();

        return view('users.index', compact('users'));
    }

    /**
     * Show create user form.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'role' => [
                'required',
                Rule::in([
                    'admin',
                    'pharmacist',
                    'cashier',
                    'doctor',
                ]),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display a specific user.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show edit user form.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update a user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'role' => [
                'required',
                Rule::in([
                    'admin',
                    'pharmacist',
                    'cashier',
                    'doctor',
                ]),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        /*
         * Do not overwrite the existing password
         * when no new password was entered.
         */
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        /*
         * Prevent an administrator from deleting
         * their own account.
         */
        if ($user->id === auth()->id()) {

            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'You cannot delete your own account.'
                );
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User deleted successfully.'
            );
    }

    /**
     * Generate a 6-digit password reset code.
     *
     * Admin only.
     */
    public function generateResetCode(User $user)
    {
        /*
         * Generate a secure random 6-digit code.
         */
        $code = (string) random_int(100000, 999999);

        /*
         * Store only the hashed version of the code.
         *
         * If the user already has a reset code,
         * it will be replaced with the new one.
         */
        PasswordResetCode::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'code' => Hash::make($code),
                'expires_at' => now()->addMinutes(15),
            ]
        );

        return redirect()
            ->route('users.index')
            ->with(
                'reset_code',
                $code
            )
            ->with(
                'reset_user',
                $user->name
            );
    }
}