<?php

namespace App\Http\Controllers\Client\Auth;

use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordReset;
use App\Notifications\VerificationCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Client Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating client for the application and
    | redirecting them to your client home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    const TYPE = 'client';
    public function __construct()
    {
        $this->middleware('client.guest')->except(['resendVerificationMail', 'accountRestricted', 'logout', 'verifyEmail']);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('web');
    }

    // Login
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'email'     => 'required|email',
                'password'  => 'required',
            ]);

            $credentials = [
                'email'     => $request->email,
                'password'  => $request->password,
            ];

            if (self::guard()->attempt($credentials, $request->active ? 1 : 0)) {
                $request->session()->regenerate();
                return redirect()->route('client.dashboard');
            }

            return back()->withError('The provided credentials do not match our records.')->onlyInput('email');
        }

        $page_title = 'Login';
        $page_description = 'Login User';
        $logo = "images/logo.png";
        $logoText = "images/logo-text.png";
        $type = Self::TYPE;
        return view('auth.login', compact('page_title', 'page_description', 'logo', 'logoText', 'type'));
    }

    // Register
    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'name'          => 'required|min:4|max:40',
                'email'         => 'required|email|unique:clients,email|max:60',
                'password'      => 'required|min:8',
            ]);

            $insert = [
                'status'    => '1',
                'code'      => Str::random(6),
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
            ];

            $user = Client::create($insert);
            if ($user && self::guard()->attempt($request->only(['email', 'password']), $request->active ? 1 : 0)) {
                Notification::route('mail', $request->email)->notify(new VerificationCode($insert['code']));
                $request->session()->regenerate();
                return redirect()->route('client.dashboard');
            }

            return back()->withError('The provided credentials do not match our records.')->onlyInput('email');
        }

        $page_title = 'Register';
        $page_description = 'Register User';
        $logo = "images/logo.png";
        $logoText = "images/logo-text.png";
        $type = Self::TYPE;
        return view('auth.register', compact('page_title', 'page_description', 'logo', 'logoText', 'type'));
    }

    //forgotPassword
    public function forgotPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|email|exists:users',
            ]);

            $token = Str::random(64);
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token
            ]);

            $link = route('client.reset-password.get', $token);
            Notification::route('mail', $request->email)->notify(new PasswordReset($link));

            return redirect()->route('client.login')->withSuccess('Check Your Email To Reset Password !!');
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
            'email'                 => 'required|email|exists:users',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withError('Invalid token!');
        }

        $user = Client::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        if ($user) {
            DB::table('password_resets')->where(['email' => $request->email])->delete();
            return redirect()->route('client.login')->withSuccess('Password Reset Successfully !!');
        }
        return redirect()->route('client.forgot-password')->withError('Token Expired Please Try Again.');
    }

    //Verify Email
    public function  verifyEmail(Request $request)
    {
        if (self::guard()->check()) {
            if ($request->isMethod('post')) {
                $request->validate([
                    'code' => 'required|min:6|max:6',
                ]);
    
                $find = Client::whereCode($request->code)->whereId(self::guard()->user()->id)->first();
                if($find){
                    $find->email_verified_at = date('Y-m-d h:m:s');
                    $find->save();
                    return redirect()->route('client.dashboard')->withError('Email account verified !!');
                }
                return redirect()->back()->withError('Please enter a valid code.');
            }
            $page_title = 'Verify Email';
            $page_description = 'Verify Email';
            return view('auth.lockscreen', compact('page_title', 'page_description'));
        }
        return redirect()->route('client.login');
    }

    //resendVerificationMail
    public function resendVerificationMail()
    {
        if (self::guard()->check()) {
            $code = Str::random(6);
            Client::whereId(self::guard()->user()->id)->update(['code' => $code]);
            Notification::route('mail', self::guard()->user()->email)->notify(new VerificationCode($code));
            return redirect()->back()->withSuccess('New verification code sent. Check your email account.');
        }
        return redirect()->route('client.login');
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
        return redirect()->route('client.login');
    }

    //logout
    public function logout()
    {
        if (self::guard()->check()) {
            self::guard()->logout();
            return redirect()->route('client.login');
        }
        return redirect()->route('client.login');
    }
}
