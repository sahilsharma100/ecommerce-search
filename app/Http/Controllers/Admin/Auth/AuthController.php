<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating admin for the application and
    | redirecting them to your admin home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    const TYPE = 'admin';

    public function __construct()
    {
        $this->middleware('admin.guest')->except(['accountRestricted', 'logout']);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    // Login
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {

            $this->validate($request, [
                'email'         => 'required|email',
                'password'      => 'required',
            ]);

            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];

            if (self::guard()->attempt($credentials, $request->active ? 1 : 0)) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }

            return back()->withError('The provided credentials do not match our records.')->onlyInput('email');
        }

        $page_title = 'Login';
        $page_description = 'Login Admin dashboard';
        $logo = "images/logo.png";
        $logoText = "images/logo-text.png";
        $type = Self::TYPE;
        return view('auth.login', compact('page_title', 'page_description', 'logo', 'logoText', 'type'));
    }

    //forgotPassword
    public function forgotPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|email|exists:users',
            ]);

            $token = Str::random(64);
            DB::table('admin_password_resets')->insert([
                'email' => $request->email,
                'token' => $token
            ]);

            $link = route('admin.reset-password.get', $token);
            Notification::route('mail', $request->email)->notify(new PasswordReset($link));

            return redirect()->route('admin.login')->withSuccess('Check Your Email To Reset Password !!');
        }

        $page_title = 'Page Forgot Password';
        $page_description = 'Page Forgot Password';
        $type = Self::TYPE;
        return view('auth.forgot_password', compact('page_title', 'page_description', 'type'));
    }

    //Show Reset Password
    public function showResetPassword(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    //Submit Reset Password
    public function submitResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('admin_password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withError('Invalid token!');
        }

        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        if ($user) {
            DB::table('admin_password_resets')->where(['email' => $request->email])->delete();
            return redirect()->route('admin.login')->withSuccess('Password Reset Successfully !!');
        }
        return redirect()->route('admin.forgot-password')->withError('Token Expired Please Try Again.');
    }

    //accountRestricted
    public function accountRestricted()
    {
        if (self::guard()->check() && self::guard()->user()->status == 0) {
            $page_title = 'Account Restricted';
            $page_description = 'Account Restricted';
            $type = Self::TYPE;
            return view('auth.account-restricted', compact('page_title', 'page_description', 'type'));
        }
        return redirect()->route('admin.login');
    }

    //logout
    public function logout()
    {
        if (self::guard()->check()) {
            self::guard()->logout();
            return redirect()->route('admin.login');
        }
        return redirect()->route('admin.login');
    }
}
