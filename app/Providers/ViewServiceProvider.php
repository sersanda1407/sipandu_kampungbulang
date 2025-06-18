<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\DataKk;
use App\DataRt;
use App\DataRw;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

public function boot()
{
    View::composer('*', function ($view) {
        $user = Auth::user();
        $inboxCount = 0;

        if ($user) {
            $rw_user_id = \App\DataRw::where('user_id', $user->id)->value('id');
            $rt_user_id = \App\DataRt::where('user_id', $user->id)->value('id');

            $query = \App\DataKk::where('verifikasi', 'pending');

            if ($rw_user_id) {
                $query->where('rw_id', $rw_user_id);
            }

            if ($rt_user_id) {
                $query->where('rt_id', $rt_user_id);
            }

            // Kalau superadmin, tidak pakai filter

            $inboxCount = $query->count();
        }

        $view->with('inboxCount', $inboxCount);
    });
}

}
