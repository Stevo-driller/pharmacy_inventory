@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')

<div style="
    max-width: 450px;
    margin: 50px auto;
">

    <div style="
        background: #ffffff;
        padding: 30px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.06);
    ">

        <div style="
            text-align: center;
            margin-bottom: 25px;
        ">

            <div style="
                font-size: 35px;
                margin-bottom: 10px;
            ">
                🔐
            </div>

            <h2 style="
                margin: 0;
                color: #1e293b;
            ">
                Forgot Password?
            </h2>

            <p style="
                margin-top: 8px;
                color: #64748b;
                font-size: 13px;
                line-height: 1.5;
            ">
                Enter your registered email address.
                The system administrator will provide you
                with a 6-digit password reset code.
            </p>

        </div>


        @if(session('status'))

            <div style="
                margin-bottom: 18px;
                padding: 11px 13px;
                background: #eff6ff;
                border: 1px solid #bfdbfe;
                color: #1e40af;
                border-radius: 6px;
                font-size: 13px;
                line-height: 1.5;
            ">
                {{ session('status') }}
            </div>

        @endif


        @if($errors->any())

            <div style="
                margin-bottom: 18px;
                padding: 11px 13px;
                background: #fef2f2;
                border: 1px solid #fecaca;
                color: #b91c1c;
                border-radius: 6px;
                font-size: 13px;
            ">

                {{ $errors->first() }}

            </div>

        @endif


        <form method="POST"
              action="{{ route('password.request.code') }}">

            @csrf


            <div style="
                margin-bottom: 18px;
            ">

                <label style="
                    display: block;
                    margin-bottom: 6px;
                    color: #334155;
                    font-size: 13px;
                    font-weight: 600;
                ">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="Enter your registered email"
                    style="
                        width: 100%;
                        height: 42px;
                        padding: 8px 11px;
                        border: 1px solid #cbd5e1;
                        border-radius: 6px;
                        box-sizing: border-box;
                        font-size: 13px;
                    "
                >

            </div>


            <button type="submit"
                    style="
                        width: 100%;
                        height: 42px;
                        border: none;
                        border-radius: 6px;
                        background: #1e3a8a;
                        color: #ffffff;
                        font-size: 13px;
                        font-weight: 700;
                        cursor: pointer;
                    ">
                Continue
            </button>

        </form>


        <div style="
            text-align: center;
            margin-top: 18px;
        ">

            <a href="{{ route('login') }}"
               style="
                    color: #1e3a8a;
                    font-size: 13px;
                    text-decoration: none;
                    font-weight: 600;
               ">
                ← Back to Login
            </a>

        </div>

    </div>

</div>

@endsection