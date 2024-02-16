<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Profile extends Controller
{
    public function dashboard() {
        return view('admin.layouts.app');
    }

    public function logout() {
        auth()->logout();
        return redirect()->route('getAdminLogin')->with('success', 'You have been successfully logged out');
    }
}
