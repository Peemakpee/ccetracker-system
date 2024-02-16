<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestAccess;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RequestAccessController extends Controller
{
    public function requestStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'program' => 'required|string',
        ]);

        $requestAccess = RequestAccess::create($request->all());

        // $adminEmail = $this->getAdminEmail();

        // $requesterEmail = $this->getRequesterEmail($request->input('email'));

        // $data = [
        //     'name' => $request->input('name'),
        //     'email' => $request->input('email'),
        //     'program' => $request->input('program'),
        // ];

        // Mail::to($requesterEmail)->send(new \App\Mail\RegistrationRequested($data));
        // if ($adminEmail) {
        //     Mail::to($adminEmail)->send(new \App\Mail\AdminRegistrationRequested($data));
        // }

        return response()->json($requestAccess, 201);
    }

    private function getRequesterEmail($email)
    {
        $requester = RequestAccess::where('email', $email)->first();

        if ($requester) {

            return $requester->email;
        }

        return null;
    }

    private function getAdminEmail()
    {
        $admin = User::where('is_aa', true)->first();

        if ($admin) {
            return $admin->email;
        }
        return null;
    }

    public function getRegisterRequests()
    {
        $pendingRequests = RequestAccess::whereNull('approved')->get();
        return response()->json($pendingRequests);
    }

    public function approveRequests($id)
    {
        try {
            $request = RequestAccess::findOrFail($id);

            $request->approved = true;
            $request->save();

            $requesterEmail = $request->email;
            $requesterName = $request->name;

            $registrationPageUrl = env('VUE_APP_URL') . '/user/register';
            $data = [
                'name' => $requesterName,
                'registerUrl' => $registrationPageUrl,

            ];

            Mail::to($requesterEmail)->send(new \App\Mail\ApprovedRequest($data));


            return response()->json(['message' => 'Request approved successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error while approving request: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to approve request'], 500);
        }
    }

    public function disapproveRequests($id)
    {
        try {
            $request = RequestAccess::findOrFail($id);

            $request->approved = false;
            $request->save();

            $requesterEmail = $request->email;
            $requesterName = $request->name;

            $data = [
                'name' => $requesterName,
            ];

            Mail::to($requesterEmail)->send(new \App\Mail\DisapprovedRequest($data));

           
            return response()->json(['message' => 'Request disapproved successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to disapprove request'], 500);
        }
    }
}
