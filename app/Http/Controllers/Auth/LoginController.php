<?php

namespace App\Http\Controllers\Auth;

use App\DataPenduduk;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo ='dashboard';

    public function redirectTo()
    {
        return Session::get('backUrl') ? Session::get('backUrl') :   $this->redirectTo;
    }

    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
        $this->middleware('guest')->except('logout');
        Session::put('backUrl', URL::previous());
    }

    public function authenticated(Request $request, $user)
    {
        // $penduduk = DataPenduduk::get();
        $penduduk = Auth::user();
        // dd($user->Kk[0]->id);

        if ($user->hasRole('superadmin')) {
            return redirect()->route('dashboard')->with('toast_success', 'Welcome,' . '&nbsp;' . $user->name);
        } elseif ($user->hasRole('rw')) {
            return redirect()->route('dashboard')->with('toast_success', 'Welcome,' . '&nbsp;' . $user->name);
        } elseif ($user->hasRole('rt')) {
            return redirect()->route('dashboard')->with('toast_success', 'Welcome,' . '&nbsp;' . $user->name);
        } elseif ($user->hasRole('warga')) {
            return redirect()->route('dashboard')->with('toast_success', 'Welcome,' . '&nbsp;' . $user->name);
        }
        return redirect()->route('login');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }
}
