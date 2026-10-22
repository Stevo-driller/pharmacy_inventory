<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
            ],
        ]);

        /*
         * Check whether the email exists first.
         */
        $user = User::where(
            'email',
            $credentials['email']
        )->first();

        /*
         * Email does not exist.
         */
        if (!$user) {
            return back()
                ->withErrors([
                    'email' =>
                        'Email address not found. Please check your email address.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
         * Email exists, but password is wrong.
         */
        if (!Hash::check(
            $credentials['password'],
            $user->password
        )) {
            return back()
                ->withErrors([
                    'password' =>
                        'Incorrect password. Please try again or use Forgot Password.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
         * Log the user in.
         */
        Auth::login(
            $user,
            $request->boolean('remember')
        );

        /*
         * Regenerate the session ID to help prevent
         * session fixation attacks.
         */
        $request->session()->regenerate();

        /*
         * Redirect each role to its correct dashboard.
         *
         * Do not use redirect()->intended() here because
         * a previously requested URL could send the user
         * to a page their role cannot access.
         */
        if ($user->role === 'doctor') {
            return redirect()->route('doctor.dashboard');
        }

        if (in_array(
            $user->role,
            ['admin', 'pharmacist', 'cashier'],
            true
        )) {
            return redirect()->route('dashboard');
        }

        /*
         * Reject unexpected roles instead of sending
         * unauthorized users to a protected dashboard.
         */
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors([
                'email' =>
                    'Your account does not have a recognized role. Please contact the administrator.',
            ])
            ->withInput(
                $request->only('email')
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Forgot Password
    |--------------------------------------------------------------------------
    */

    /**
     * Show the forgot password page.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle password reset request.
     *
     * No email is sent.
     */
    public function requestResetCode(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
            ],
        ]);

        $user = User::where(
            'email',
            $validated['email']
        )->first();

        /*
         * Avoid exposing unnecessary account information.
         */
        if (!$user) {
            return back()
                ->with(
                    'status',
                    'If the account exists, please contact the system administrator for a 6-digit reset code.'
                )
                ->withInput();
        }

        /*
         * The administrator generates the reset code
         * through User Management.
         */
        return redirect()
            ->route('password.verify')
            ->with(
                'status',
                'Your request has been received. Please contact the system administrator for your 6-digit reset code.'
            )
            ->with(
                'reset_email',
                $user->email
            );
    }

    /**
     * Show reset-code verification page.
     */
    public function showVerifyResetCode()
    {
        return view('auth.verify-reset-code');
    }

    /**
     * Verify the 6-digit code and change password.
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
            ],
            'code' => [
                'required',
                'digits:6',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        /*
         * Find the user.
         */
        $user = User::where(
            'email',
            $validated['email']
        )->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'email' =>
                        'Invalid email address or reset code.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
         * Find the reset code belonging to this user.
         */
        $resetCode = PasswordResetCode::where(
            'user_id',
            $user->id
        )->first();

        if (!$resetCode) {
            return back()
                ->withErrors([
                    'code' =>
                        'No reset code was found. Please contact the administrator.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
         * Check whether the reset code has expired.
         */
        if ($resetCode->expires_at->isPast()) {
            $resetCode->delete();

            return back()
                ->withErrors([
                    'code' =>
                        'This reset code has expired. Please contact the administrator for a new code.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
         * Compare the submitted code with its
         * hashed database value.
         */
        if (!Hash::check(
            $validated['code'],
            $resetCode->code
        )) {
            return back()
                ->withErrors([
                    'code' =>
                        'The reset code is incorrect.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
         * Update the password.
         */
        $user->update([
            'password' => Hash::make(
                $validated['password']
            ),
        ]);

        /*
         * Delete the code so it cannot be reused.
         */
        $resetCode->delete();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Your password has been changed successfully. You can now sign in.'
            );
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
