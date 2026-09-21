
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Reset Password - Pharmacy Management System</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #f3f6fb;

            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 440px;

            padding: 20px;
        }

        .card {
            background: #ffffff;

            border-radius: 14px;

            padding: 35px;

            box-shadow:
                0 10px 35px
                rgba(0, 0, 0, 0.08);
        }

        .logo {
            width: 65px;
            height: 65px;

            margin: 0 auto 15px;

            background: #1e3a8a;

            color: white;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 30px;
        }

        h1 {
            text-align: center;

            margin: 0 0 8px;

            color: #1e3a8a;

            font-size: 24px;
        }

        .subtitle {
            text-align: center;

            color: #6b7280;

            font-size: 14px;

            line-height: 1.5;

            margin: 0 0 25px;
        }

        .info {
            background: #eff6ff;

            border:
                1px solid #bfdbfe;

            color: #1e40af;

            padding: 13px 14px;

            border-radius: 8px;

            font-size: 13px;

            line-height: 1.5;

            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;

            margin-bottom: 7px;

            font-weight: 600;

            color: #374151;

            font-size: 14px;
        }

        input {
            width: 100%;

            padding: 12px 14px;

            border:
                1px solid #d1d5db;

            border-radius: 8px;

            font-size: 15px;

            outline: none;

            transition:
                border-color 0.2s,
                box-shadow 0.2s;
        }

        input:focus {
            border-color: #1e3a8a;

            box-shadow:
                0 0 0 3px
                rgba(30, 58, 138, 0.1);
        }

        .code-input {
            text-align: center;

            letter-spacing: 8px;

            font-size: 22px;

            font-weight: 700;
        }

        .button {
            width: 100%;

            padding: 13px;

            border: none;

            border-radius: 8px;

            background: #1e3a8a;

            color: white;

            font-size: 16px;

            font-weight: 600;

            cursor: pointer;

            transition:
                background 0.2s,
                transform 0.1s;
        }

        .button:hover {
            background: #172f70;
        }

        .button:active {
            transform: scale(0.99);
        }

        .error {
            background: #fee2e2;

            color: #991b1b;

            padding: 12px 14px;

            border-radius: 7px;

            margin-bottom: 18px;

            font-size: 14px;

            border:
                1px solid #fecaca;
        }

        .back {
            display: block;

            text-align: center;

            margin-top: 18px;

            color: #1e3a8a;

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;
        }

        .back:hover {
            text-decoration: underline;
        }

        .footer {
            text-align: center;

            margin-top: 20px;

            color: #9ca3af;

            font-size: 12px;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <div class="logo">
            💊
        </div>

        <h1>
            Reset Password
        </h1>

        <p class="subtitle">
            Enter the 6-digit reset code provided by your system administrator and choose a new password.
        </p>


        {{-- Information message --}}

        @if (session('status'))

            <div class="info">
                {{ session('status') }}
            </div>

        @endif


        {{-- Validation errors --}}

        @if ($errors->any())

            <div class="error">

                {{ $errors->first() }}

            </div>

        @endif


        <form
            action="{{ route('password.reset') }}"
            method="POST"
        >

            @csrf


            {{-- Email --}}

            <div class="form-group">

                <label for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', session('reset_email')) }}"
                    placeholder="Enter your email address"
                    required
                    autofocus
                >

            </div>


            {{-- 6-digit code --}}

            <div class="form-group">

                <label for="code">
                    6-Digit Reset Code
                </label>

                <input
                    type="text"
                    id="code"
                    name="code"
                    class="code-input"
                    maxlength="6"
                    minlength="6"
                    pattern="[0-9]{6}"
                    inputmode="numeric"
                    placeholder="000000"
                    value="{{ old('code') }}"
                    required
                >

            </div>


            {{-- New password --}}

            <div class="form-group">

                <label for="password">
                    New Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter new password"
                    required
                >

            </div>


            {{-- Confirm password --}}

            <div class="form-group">

                <label for="password_confirmation">
                    Confirm New Password
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Confirm new password"
                    required
                >

            </div>


            <button
                type="submit"
                class="button"
            >
                Change Password
            </button>

        </form>


        <a
            href="{{ route('login') }}"
            class="back"
        >
            ← Back to Login
        </a>


        <div class="footer">
            Pharmacy Management System
        </div>

    </div>

</div>

</body>

</html>


