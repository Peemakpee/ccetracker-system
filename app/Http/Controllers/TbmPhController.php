<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\TbmPh;
use Illuminate\Support\Facades\Mail;
use App\Models\UploadFile;
use App\Models\User;

class TbmPhController extends Controller
{
    public function storeTbmPH(Request $request)
    {
        Log::debug('Store method was called');
        $request->validate([
            'document_id' => 'required|integer',
            'comment' => 'nullable|string',
            'file_path' => 'nullable|string',
            'firebaseUrl_wSign' => 'required|url', 
            'date_upladedByUser' => 'required|date',
            'deliverable_type' => 'required|string',
            'file_name' => 'required|string',
            'status' => 'required|string',
            'academic_year' => 'required|string',
            'program' => 'required|string',
            'user_id' => 'required|integer',
            'user_name' => 'required|string',
        ]);

        $uploadTbmPh = TbmPh::create($request->all());

        // $facultyEmail = $this->getFacultyEmail($request->input('user_name'));
        // $forRevisionPhdata = [
        //     'user_name' => $request->input('user_name'),
        //     'program' => $request->input('program'),
        //     'file_name' => $request->input('file_name'),
        //     'deliverable_type' => $request->input('deliverable_type'),
        // ];

        // Mail::to($facultyEmail)->send(new \App\Mail\ForRevisionByPh($forRevisionPhdata));

        return response()->json($uploadTbmPh, 201);
    }

    public function getTbmPh()
    {
        $allData = TbmPh::all();
        return response()->json($allData);
    }

    public function getTbmFiles($userId)
    {
        $userFiles = TbmPh::where('user_Id', $userId)->get();
        return response()->json($userFiles);
    }

    private function getFacultyEmail($userName)
    {
        $facultyUser = User::where([
            ['name', '=', $userName],
            ['is_admin', '=', false]
        ])->first();

        return $facultyUser ? $facultyUser->email : null;
    }
}
