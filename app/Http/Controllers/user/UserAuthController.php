<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Illuminate\Validation\Rule;



class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'program' => 'required|string',
            'email' => 'required|email|string|unique:users,email',
            'password' => [
                'required',
                'confirmed',

                Password::min(8)->mixedCase()->numbers()->symbols()
            ]
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'program' => $data['program'],
            'password' => bcrypt($data['password'])
        ]);
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)

    {
        $credentials = $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => [
                'required',
            ],
            'remember' => 'boolean'
        ]);
        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);

        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'error' => 'Email or password is incorrect'
            ], 422);
        }

        $user = Auth::user();
        if ($user->is_admin) {
            Auth::logout();
            return response([
                'message' => 'You don\'t have permission to authenticate as user'
            ], 403);
        }

        $user = Auth::user();
        $token = $user->createToken(name: 'main')->plainTextToken;
        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout()
    {
        /**@var User $user */
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return response([
            'success' => true
        ]);
    }

    public function getUser(Request $request)
    {
        $user = $request->user();
        return response([
            'user' => new UserResource($user),
            'userId' => $user->id,
        ]);
    }

    public function getUsers()
    {
        $allData = User::all();
        return response()->json($allData);
    }

    public function getUsersByProgram()
    {
        $usersByProgram = User::all()->groupBy('program');

        return response()->json($usersByProgram);
    }

    public function getProgramHead($program)
    {
        $programHead = User::where('program', $program)
            ->where('is_admin', true)
            ->where('is_aa', false)
            ->where('is_dean', false)
            ->first();

        return response()->json($programHead);
    }

    public function getFacultyOption()
    {
        $filteredUsers = User::where('is_admin', false)
            ->where('is_aa', false)
            ->where('is_dean', false)
            ->pluck('name');

        return response()->json($filteredUsers);
    }


    public function validateRecipient()
    {
        $userNames = User::pluck('name')->all();

        return response()->json($userNames);
    }

    public function getRecipient()
    {
        $userNames = User::pluck('name')->all();

        return response()->json($userNames);
    }

    public function getProgramOptions()
    {
        $programOptions = User::select('program')
            ->whereNotIn('program', ['AA', 'DEAN'])
            ->distinct()
            ->pluck('program');

        return response()->json($programOptions);
    }

    public function setUserStatus(Request $request, $userID)
    {
        $request->validate([
            'status' => [
                'required',
                Rule::in(['on_study_leave', 'on_leave', 'no_load', 'active', 'deactivated']),
            ],
        ]);

        try {
            $user = User::findOrFail($userID);

            $user->status = $request->input('status');
            $user->save();

            return response()->json(['message' => 'User status updated successfully']);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Error updating user status', 'message' => $e->getMessage()], 500);
        }
    }

    public function getCurrentUserName(Request $request)
    {
        $userID = $request->input('userID');

        // Fetch the user by ID
        $user = User::find($userID);

        if ($user) {
            // Return the user's name
            return response()->json(['name' => $user->name]);
        } else {
            // User not found
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function getCurrentUser($currentUser)
    {
    
        $user = User::find($currentUser);

        if ($user) {
            return response()->json(['name' => $user->name]);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

}
