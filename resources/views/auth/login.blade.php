<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login - Pharmacy Management System</title>

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

        .login-container {
            width: 100%;
            max-width: 420px;

            padding: 20px;
        }

        .login-card {
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

        .title {
            text-align: center;

            margin: 0 0 5px;

            color: #1e3a8a;

            font-size: 24px;
        }

        .subtitle {
            text-align: center;

            color: #6b7280;

            font-size: 14px;

            margin: 0 0 30px;
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

        input[type="email"],
        input[type="password"],
        input[type="text"] {
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

        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            border-color: #1e3a8a;

            box-shadow:
                0 0 0 3px
                rgba(30, 58, 138, 0.1);
        }

        /*
        |--------------------------------------------------------------------------
        | Password field
        |--------------------------------------------------------------------------
        */

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 48px;
        }

        .toggle-password {
            position: absolute;

            right: 12px;
            top: 50%;

            transform: translateY(-50%);

            border: none;

            background: transparent;

            cursor: pointer;

            font-size: 19px;

            color: #6b7280;

            padding: 4px;

            line-height: 1;
        }

        .toggle-password:hover {
            color: #1e3a8a;
        }

        /*
        |--------------------------------------------------------------------------
        | Remember + Forgot Password
        |--------------------------------------------------------------------------
        */

        .login-options {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 10px;

            margin-bottom: 20px;
        }

        .remember {
            display: flex;

            align-items: center;

            gap: 8px;

            margin: 0;

            font-size: 14px;

            color: #4b5563;

            font-weight: normal;
        }

        .remember input {
            width: 16px;
            height: 16px;

            margin: 0;
        }

        .forgot-password {
            color: #1e3a8a;

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;

            white-space: nowrap;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        /*
        |--------------------------------------------------------------------------
        | Login button
        |--------------------------------------------------------------------------
        */

        .login-btn {
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

        .login-btn:hover {
            background: #172f70;
        }

        .login-btn:active {
            transform: scale(0.99);
        }

        /*
        |--------------------------------------------------------------------------
        | Error message
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Success message
        |--------------------------------------------------------------------------
        */

        .success {
            background: #dcfce7;

            color: #166534;

            padding: 12px 14px;

            border-radius: 7px;

            margin-bottom: 18px;

            font-size: 14px;

            border:
                1px solid #bbf7d0;
        }

        .footer {
            text-align: center;

            margin-top: 20px;

            color: #9ca3af;

            font-size: 12px;
        }

        @media (max-width: 480px) {

            .login-card {
                padding: 25px 20px;
            }

            .login-options {
                align-items: flex-start;
            }

            .forgot-password {
                font-size: 13px;
            }

        }

    </style>

</head>

<body>

<div class="login-container">

    <div class="login-card">

        <div class="logo">
            💊
        </div>

        <h1 class="title">
            Pharmacy System
        </h1>

        <p class="subtitle">
            Sign in to access your account
        </p>


        {{-- Success message --}}

        @if (session('status'))

            <div class="success">
                {{ session('status') }}
            </div>

        @endif


        {{-- Login error --}}

        @if ($errors->any())

            <div class="error">

                {{ $errors->first() }}

            </div>

        @endif


        <form
            action="{{ route('login.submit') }}"
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
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    required
                    autofocus
                >

            </div>


            {{-- Password --}}

            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        id="togglePassword"
                        aria-label="Show password"
                    >
                        👁
                    </button>

                </div>

            </div>


            {{-- Remember me + Forgot password --}}

            <div class="login-options">

                <label class="remember">

                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                    >

                    Remember me

                </label>
         


                <a
                    href="{{ route('password.request') }}"
                    class="forgot-password"
                >
                    Forgot Password?
                </a>

            </div>


            {{-- Login button --}}

            <button
                type="submit"
                class="login-btn"
            >
                Sign In
            </button>

        </form>


        <div class="footer">
            Pharmacy Management System
        </div>

    </div>

</div>


<script>

    const togglePassword =
        document.getElementById('togglePassword');

    const password =
        document.getElementById('password');


    togglePassword.addEventListener('click', function () {

        const isPassword =
            password.type === 'password';

        password.type =
            isPassword ? 'text' : 'password';

        this.textContent =
            isPassword ? '🙈' : '👁';

        this.setAttribute(
            'aria-label',
            isPassword
                ? 'Hide password'
                : 'Show password'
        );

    });

</script>

</body>

</html>