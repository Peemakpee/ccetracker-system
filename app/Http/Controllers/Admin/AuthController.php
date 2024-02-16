<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;


class AuthController extends Controller
{
    public function getAdminLogin()
    {
        return view('admin.auth.login');
    }

    public function postAdminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $validated = auth()->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'is_admin' => 1
        ], $request->password);

        if ($validated) {
            $user = auth()->user();
            // if ($user->is_dean && !Cache::has('send-deadline-reminders')) {
            //     Artisan::call('app:send-deadline-reminders');

            //     Cache::put('send-deadline-reminders', true, now()->endOfDay());
            // }

            Artisan::call('app:delete-old-files');

            return redirect()->route('dashboard', ['id' => $user->id])->with('success', 'Login Successful');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }
}
