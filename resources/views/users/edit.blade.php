@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="header">


<div>

    <h2>Edit User</h2>

    <p style="margin-top: 5px; color: #666;">
        Update user information and permissions.
    </p>

</div>

<a href="{{ route('users.index') }}"
   class="btn btn-secondary">
    Back
</a>


</div>

<div class="form-container">

<form method="POST"
      action="{{ route('users.update', $user) }}">

    @csrf

    @method('PUT')


    <div class="form-grid">


        {{-- NAME --}}

        <div class="form-group">

            <label for="name">
                Full Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $user->name) }}"
                required
            >

            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror

        </div>


        {{-- EMAIL --}}

        <div class="form-group">

            <label for="email">
                Email Address
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $user->email) }}"
                required
            >

            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror

        </div>


        {{-- ROLE --}}

        <div class="form-group">

            <label for="role">
                Role
            </label>

            <select id="role"
                    name="role"
                    required>

                <option value="admin"
                    {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                    Admin
                </option>

                <option value="pharmacist"
                    {{ old('role', $user->role) === 'pharmacist' ? 'selected' : '' }}>
                    Pharmacist
                </option>

                <option value="cashier"
                    {{ old('role', $user->role) === 'cashier' ? 'selected' : '' }}>
                    Cashier
                </option>

            </select>

            @error('role')
                <span class="error">{{ $message }}</span>
            @enderror

        </div>


        {{-- NEW PASSWORD --}}

        <div class="form-group">

            <label for="password">
                New Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                placeholder="Leave blank to keep current password"
            >

            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror

        </div>


        {{-- CONFIRM PASSWORD --}}

        <div class="form-group">

            <label for="password_confirmation">
                Confirm New Password
            </label>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                minlength="8"
                placeholder="Repeat new password"
            >

        </div>


    </div>


    <div class="form-actions">

        <button type="submit"
                class="btn btn-primary">
            Update User
        </button>

        <a href="{{ route('users.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>

    </div>

</form>


</div>

@endsection
