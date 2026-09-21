@extends('layouts.app')

@section('title', 'User Management')

@section('content')

<div class="header">

    <div>
        <h2>User Management</h2>

        <p style="margin-top: 5px; color: #666;">
            Manage administrators, pharmacists and cashiers.
        </p>
    </div>

    <a href="{{ route('users.create') }}"
       class="btn btn-primary">
        + Add User
    </a>

</div>


{{-- =========================================================
     SUCCESS MESSAGE
========================================================= --}}

@if(session('success'))

    <div style="
        margin-bottom: 20px;
        padding: 12px 15px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        border-radius: 7px;
    ">
        {{ session('success') }}
    </div>

@endif


{{-- =========================================================
     ERROR MESSAGE
========================================================= --}}

@if(session('error'))

    <div style="
        margin-bottom: 20px;
        padding: 12px 15px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        border-radius: 7px;
    ">
        {{ session('error') }}
    </div>

@endif


{{-- =========================================================
     PASSWORD RESET CODE
========================================================= --}}

@if(session('reset_code'))

    <div style="
        margin-bottom: 20px;
        padding: 18px;
        background: #eff6ff;
        border: 1px solid #93c5fd;
        border-radius: 8px;
    ">

        <div style="
            color: #1e3a8a;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
        ">
            Password Reset Code
        </div>

        <div style="
            color: #475569;
            font-size: 13px;
            margin-bottom: 12px;
        ">
            Give this code to
            <strong>{{ session('reset_user') }}</strong>.
            The code expires in 15 minutes.
        </div>

        <div style="
            display: inline-block;
            padding: 10px 16px;
            background: #ffffff;
            border: 2px dashed #1e3a8a;
            border-radius: 7px;
            color: #1e3a8a;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 5px;
        ">
            {{ session('reset_code') }}
        </div>

        <div style="
            margin-top: 10px;
            color: #64748b;
            font-size: 12px;
        ">
            For security, this code is not stored in plain text
            in the database.
        </div>

    </div>

@endif


<div class="table-container">

    <table>

        <thead>

            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>

        </thead>


        <tbody>

            @forelse($users as $user)

                <tr>

                    <td>
                        {{ $loop->iteration }}
                    </td>


                    <td>
                        <strong>
                            {{ $user->name }}
                        </strong>
                    </td>


                    <td>
                        {{ $user->email }}
                    </td>


                    <td>

                        @if($user->role === 'admin')

                            <span style="
                                background: #dbeafe;
                                color: #1e3a8a;
                                padding: 5px 9px;
                                border-radius: 5px;
                                font-size: 12px;
                                font-weight: bold;
                            ">
                                Admin
                            </span>

                        @elseif($user->role === 'pharmacist')

                            <span style="
                                background: #dcfce7;
                                color: #166534;
                                padding: 5px 9px;
                                border-radius: 5px;
                                font-size: 12px;
                                font-weight: bold;
                            ">
                                Pharmacist
                            </span>

                        @elseif($user->role === 'cashier')

                            <span style="
                                background: #fef3c7;
                                color: #92400e;
                                padding: 5px 9px;
                                border-radius: 5px;
                                font-size: 12px;
                                font-weight: bold;
                            ">
                                Cashier
                            </span>

                        @else

                            <span>
                                {{ ucfirst($user->role) }}
                            </span>

                        @endif

                    </td>


                    <td>
                        {{ $user->created_at->format('d M Y') }}
                    </td>


                    <td>

                        <div class="actions">

                            {{-- VIEW --}}

                            <a href="{{ route('users.show', $user) }}"
                               class="btn btn-secondary">
                                View
                            </a>


                            {{-- EDIT --}}

                            <a href="{{ route('users.edit', $user) }}"
                               class="btn btn-warning">
                                Edit
                            </a>


                            {{-- GENERATE RESET CODE --}}

                            <form method="POST"
                                  action="{{ route('users.generate-reset-code', $user) }}"
                                  onsubmit="return confirm('Generate a new 6-digit password reset code for {{ $user->name }}? The previous code, if any, will stop working.')">

                                @csrf

                                <button type="submit"
                                        class="btn"
                                        style="
                                            background: #7c3aed;
                                            color: #ffffff;
                                        ">
                                    Reset Code
                                </button>

                            </form>


                            {{-- DELETE --}}

                            @if($user->id !== auth()->id())

                                <form method="POST"
                                      action="{{ route('users.destroy', $user) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this user?')">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger">
                                        Delete
                                    </button>

                                </form>

                            @endif

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6"
                        class="empty">

                        No users found.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection