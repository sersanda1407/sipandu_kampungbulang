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
use App\Helpers\HistoryLogHelper;

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

        // Catat log login
        $role = $user->getRoleNames()->first();
        createHistoryLog('login', 'User ' . $user->name . ' (' . $role . ') melakukan login');

        // Superadmin, RT, RW langsung boleh masuk
        if ($user->hasRole('superadmin') || $user->hasRole('rw') || $user->hasRole('rt')) {
            return redirect()->route('dashboard')->with('toast_success', 'Welcome, ' . $user->name);
        }

        // Jika role-nya warga, periksa dulu status verifikasi KK
        if ($user->hasRole('warga')) {
            // Asumsikan relasi: $user->Kk() -> hasOne(DataKk)
            $kk = $user->Kk()->first(); 

            if (!$kk || $kk->verifikasi !== 'diterima') {
                // Catat log login gagal karena belum diverifikasi
                createHistoryLog('login_failed', 'User ' . $user->name . ' (warga) gagal login - data belum diverifikasi');
                
                Auth::logout(); // Paksa logout
                return redirect()->route('login')->with('error', 'Data anda belum diverifikasi atau ditolak. Silakan tunggu proses verifikasi.');
            }

            return redirect()->route('dashboard')->with('toast_success', 'Welcome, ' . $user->name);
        }

        // Jika tidak memiliki role valid
        createHistoryLog('login_failed', 'User ' . $user->name . ' gagal login - role tidak dikenali');
        return redirect()->route('login')->with('error', 'Role tidak dikenali.');
    }

    // Override method logout untuk mencatat log logout
    public function logout(Request $request)
    {
        $user = auth()->user();
        $role = $user ? $user->getRoleNames()->first() : 'Unknown';
        $userName = $user ? $user->name : 'Unknown';
        
        // Catat log logout sebelum proses logout
        createHistoryLog('logout', 'User ' . $userName . ' (' . $role . ') melakukan logout');
        
        // Lakukan proses logout
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return $this->loggedOut($request) ?: redirect('/');
    }

    public function showLoginForm()
    {
        $selectRt = DataRt::get();
        $selectRw = DataRw::get();
        return view('auth.login', compact('selectRt', 'selectRw'));
    }
}