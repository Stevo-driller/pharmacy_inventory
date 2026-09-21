@extends('layouts.app')

@section('title', 'User Details')

@section('content')

<div class="header">

<div>

    <h2>User Details</h2>

    <p style="margin-top: 5px; color: #666;">
        View account information.
    </p>

</div>

<a href="{{ route('users.index') }}"
   class="btn btn-secondary">
    Back
</a>


</div>

<div class="form-container">


<div style="display: grid; gap: 20px;">


    <div>

        <strong>Name</strong>

        <p>
            {{ $user->name }}
        </p>

    </div>


    <div>

        <strong>Email</strong>

        <p>
            {{ $user->email }}
        </p>

    </div>


    <div>

        <strong>Role</strong>

        <p>
            {{ ucfirst($user->role) }}
        </p>

    </div>


    <div>

        <strong>Account Created</strong>

        <p>
            {{ $user->created_at->format('d M Y, H:i') }}
        </p>

    </div>


    <div>

        <strong>Last Updated</strong>

        <p>
            {{ $user->updated_at->format('d M Y, H:i') }}
        </p>

    </div>


</div>


<div class="form-actions">

    <a href="{{ route('users.edit', $user) }}"
       class="btn btn-warning">
        Edit User
    </a>

    <a href="{{ route('users.index') }}"
       class="btn btn-secondary">
        Back to Users
    </a>

</div>


</div>

@endsection
