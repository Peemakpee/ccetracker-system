<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminRegister extends Controller
{
    public function showRegistrationForm()
    {
        return view('admin.auth.register');
    }

    public function postRegister(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:Program Head,Administrative Assistant,Dean',
        ]);

        $selectedRole = $request->input('role');
        $program = '';

        if ($selectedRole === 'Administrative Assistant') {
            $program = 'AA';
        } elseif ($selectedRole === 'Dean') {
            $program = 'DEAN'; 
        } else {
            $program = $request->input('program');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'program' => $program,
            'password' => Hash::make($request->password),
            'is_admin' => 1,
            'is_aa' => ($selectedRole === 'Administrative Assistant') ? 1 : 0,
            'is_dean' => ($selectedRole === 'Dean') ? 1 : 0,
        ]);

        return redirect()->route('getAdminLogin')->with('success', 'User registered successfully! You can now log in.');
    }
}
