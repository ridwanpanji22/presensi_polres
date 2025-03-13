<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(RouteServiceProvider::HOME);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'nrp' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = \App\Models\User::where('nrp', $request->nrp)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));

            // if ($user->role === 'admin') {
            //     return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
            // }

            // return redirect()->intended(RouteServiceProvider::USER_HOME);

            if ($user->role === 'admin') {
                $redirectUrl = route('admin.index');
            } else {
                $redirectUrl = route('user.index');
            }

            return redirect()->intended($redirectUrl)->with([
                'success' => 'Login berhasil!',
                'redirect_url' => $redirectUrl,
            ]);
        }

        return back()->with([
            'error' => 'NRP atau PASSWORD salah.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
