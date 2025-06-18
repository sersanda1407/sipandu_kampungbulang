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
use Illuminate\Support\Facades\Log;
use App\DataRt;
use App\DataRw;



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
    Log::info('Login Berhasil', [
        'user_id' => $user->id,
        'nama' => $user->name,
        'email' => $user->email,
        'role' => $user->getRoleNames()->first(),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'waktu' => now()->toDateTimeString(),
    ]);

    // Superadmin, RT, RW langsung boleh masuk
    if ($user->hasRole('superadmin') || $user->hasRole('rw') || $user->hasRole('rt')) {
        return redirect()->route('dashboard')->with('toast_success', 'Welcome, ' . $user->name);
    }

    // Jika role-nya warga, periksa dulu status verifikasi KK
    if ($user->hasRole('warga')) {
        // Asumsikan relasi: $user->Kk() -> hasOne(DataKk)
        $kk = $user->Kk()->first(); 

        if (!$kk || $kk->verifikasi !== 'diterima') {
            Auth::logout(); // Paksa logout
            return redirect()->route('login')->with('error', 'Data anda belum diverifikasi atau ditolak. Silakan tunggu proses verifikasi.');
        }

        return redirect()->route('dashboard')->with('toast_success', 'Welcome, ' . $user->name);
    }

    // Jika tidak memiliki role valid
    return redirect()->route('login')->with('error', 'Role tidak dikenali.');
}


    public function showLoginForm()
{
    $selectRt = DataRt::get();
    $selectRw = DataRw::get();
    return view('auth.login', compact('selectRt', 'selectRw'));
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
