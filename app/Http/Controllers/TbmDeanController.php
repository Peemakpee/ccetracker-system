<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\TbmDean;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TbmDeanController extends Controller
{
    public function storeTbmDean(Request $request)
    {
        Log::debug('Store method was called');
        $request->validate([
            'document_id' => 'required|integer',
            'comment' => 'nullable|string',
            'file_path' => 'nullable|string',
            'firebaseUrl_wSignDean' => 'required|url', 
            'date_upladedByUser' => 'required|date',
            'deliverable_type' => 'required|string',
            'file_name' => 'required|string',
            'status' => 'required|string',
            'academic_year' => 'required|string',
            'program' => 'required|string',
            'user_id' => 'required|integer',
            'user_name' => 'required|string',
        ]);

        $uploadTbmDean = TbmDean::create($request->all());

        // $facultyEmail = $this->getFacultyEmail($request->input('user_name'));
        // $forRevisionDeandata = [
        //     'user_name' => $request->input('user_name'),
        //     'program' => $request->input('program'),
        //     'file_name' => $request->input('file_name'),
        //     'deliverable_type' => $request->input('deliverable_type'),
        // ];

        // Mail::to($facultyEmail)->send(new \App\Mail\ForRevisionByDean($forRevisionDeandata));

        return response()->json($uploadTbmDean, 201);
    }

    private function getFacultyEmail($userName)
    {
        $facultyUser = User::where([
            ['name', '=', $userName],
            ['is_admin', '=', false]
        ])->first();

        return $facultyUser ? $facultyUser->email : null;
    }

    public function getTbmDeanFiles($userId)
    {
        $userFiles = TbmDean::where('user_Id', $userId)->get();
        return response()->json($userFiles);
    }
}
