@extends('layouts.app')

@section('title', 'Add User')

@section('content')

<div class="header">

<div>
    <h2>Add New User</h2>

    <p style="margin-top: 5px; color: #666;">
        Create an account for an administrator, pharmacist, cashier or doctor.
    </p>
</div>

<a href="{{ route('users.index') }}" class="btn btn-secondary">
    Back
</a>


</div>

<div class="form-container">


<form method="POST" action="{{ route('users.store') }}">

    @csrf

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
                value="{{ old('name') }}"
                required
                placeholder="Enter full name"
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
                value="{{ old('email') }}"
                required
                placeholder="Enter email address"
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

            <select id="role" name="role" required>

                <option value="">
                    -- Select Role --
                </option>

                <option value="admin"
                    {{ old('role') === 'admin' ? 'selected' : '' }}>
                    Admin
                </option>

                <option value="pharmacist"
                    {{ old('role') === 'pharmacist' ? 'selected' : '' }}>
                    Pharmacist
                </option>

                <option value="cashier"
                    {{ old('role') === 'cashier' ? 'selected' : '' }}>
                    Cashier
                </option>

                <option value="doctor"
                    {{ old('role') === 'doctor' ? 'selected' : '' }}>
                    Doctor
                </option>

            </select>

            @error('role')
                <span class="error">{{ $message }}</span>
            @enderror

        </div>


        {{-- PASSWORD --}}

        <div class="form-group">

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                required
                minlength="8"
                placeholder="Minimum 8 characters"
            >

            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror

        </div>


        {{-- CONFIRM PASSWORD --}}

        <div class="form-group">

            <label for="password_confirmation">
                Confirm Password
            </label>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                minlength="8"
                placeholder="Confirm password"
            >

        </div>


    </div>


    <div class="form-actions">

        <button type="submit" class="btn btn-primary">
            Create User
        </button>

        <a href="{{ route('users.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>

    </div>

</form>


</div>

@endsection