<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Forgot Password - Pharmacy Management System</title>

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
            max-width: 420px;

            padding: 20px;
        }

        .card {
            background: white;

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

            color: #1e3a8a;

            font-size: 24px;

            margin: 0 0 8px;
        }

        .description {
            text-align: center;

            color: #6b7280;

            font-size: 14px;

            line-height: 1.5;

            margin-bottom: 25px;
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
        }

        input:focus {
            border-color: #1e3a8a;

            box-shadow:
                0 0 0 3px
                rgba(30, 58, 138, 0.1);
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
        }

        .button:hover {
            background: #172f70;
        }

        .error {
            background: #fee2e2;

            color: #991b1b;

            padding: 12px;

            border-radius: 7px;

            margin-bottom: 18px;

            font-size: 14px;
        }

        .success {
            background: #dcfce7;

            color: #166534;

            padding: 12px;

            border-radius: 7px;

            margin-bottom: 18px;

            font-size: 14px;
        }

        .back {
            display: block;

            text-align: center;

            margin-top: 20px;

            color: #1e3a8a;

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;
        }

        .back:hover {
            text-decoration: underline;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <div class="logo">
            🔑
        </div>

        <h1>
            Forgot Password?
        </h1>

        <p class="description">
            Enter the email address associated with
            your account and we will send you a link
            to reset your password.
        </p>


        @if (session('status'))

            <div class="success">
                {{ session('status') }}
            </div>

        @endif


        @if ($errors->any())

            <div class="error">
                {{ $errors->first() }}
            </div>

        @endif


        <form
            method="POST"
            action="{{ route('password.email') }}"
        >

            @csrf

            <div class="form-group">

                <label for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your email address"
                    required
                    autofocus
                >

            </div>


            <button
                type="submit"
                class="button"
            >
                Send Reset Link
            </button>

        </form>


        <a
            href="{{ route('login') }}"
            class="back"
        >
            ← Back to Login
        </a>

    </div>

</div>

</body>

</html>