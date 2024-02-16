<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CompliedChanges;
use Illuminate\Support\Facades\Log;
use App\Models\ArchiveUploads;
use App\Models\User;

class CompliedChangesController extends Controller
{
    public function storeCompliedChanges(Request $request)
    {
        Log::debug('Store method was called');

        $request->validate([
            'document_id' => 'required|integer',
            'file_path' => 'nullable|string',
            'firebaseUrl_complied' => 'required|url', 
            'date_upladedByUser' => 'required|date',
            'deliverable_type' => 'required|string',
            'file_name' => 'required|string',
            'status' => 'required|string',
            'academic_year' => 'required|string',
            'program' => 'required|string',
            'user_id' => 'required|integer',
            'user_name' => 'required|string',
            'change_description' => 'required|string',


        ]);

        $uploadFile = CompliedChanges::create($request->all());

       
        // $deanEmail = $this->getDeanEmail();       
        // $data = [
        //     'user_name' => $request->input('user_name'),
        //     'program' => $request->input('program'),
        //     'file_name' => $request->input('file_name'),
        //     'deliverable_type' => $request->input('deliverable_type'),
        // ];

        // Mail::to($deanEmail)->send(new \App\Mail\CompliedChanges($data));

        return response()->json($uploadFile, 201);
    }

    private function getDeanEmail()
    {
        $deanUser = User::where('is_dean', 1)->first();

        return $deanUser ? $deanUser->email : null;
    }



    public function getCompiledChanges()
    {
        try {
            
            $compiledChanges = CompliedChanges::all();
          
            return response()->json($compiledChanges);
        } catch (\Exception $e) {
     
            Log::error('Error fetching complied changes: ' . $e->getMessage());

            return response()->json(['error' => 'An error occurred while fetching the complied changes.'], 500);
        }
    }

    public function getCompiledChangesProgram($program)
    {
        if ($program === 'dean') {
            $uploads = CompliedChanges::where('program', $program)->get();
        } else {
            $uploads = CompliedChanges::where('program', $program)->get();
        }

        if ($uploads->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'No documents found']);
        }

        return response()->json($uploads);
    }

    public function archiveCompliedChanges($id)
    {
        Log::info('Received archive request for ID: ' . $id);
        $upload = CompliedChanges::where('document_id', $id)->first();

        if (!$upload) {
            return response()->json(['message' => 'Approved File by PH not found'], 404);
        }

        $archiveUpload = new ArchiveUploads();
        $archiveUpload->document_id = $upload->document_id;
        $archiveUpload->status = 'complied_changes';
        $archiveUpload->file_name = $upload->file_name;
        $archiveUpload->firebase_url = $upload->firebaseUrl_complied;
        $archiveUpload->program = $upload->program;
        $archiveUpload->user_id = $upload->user_id;
        $archiveUpload->user_name = $upload->user_name;
        $archiveUpload->academic_year = $upload->academic_year;
        $archiveUpload->date_uploadedByUser = $upload->date_upladedByUser;
        $archiveUpload->deliverable_type = $upload->deliverable_type;

        $archiveUpload->save();

        $upload->delete();

        return response()->json(['message' => 'Successfully archived']);
    }
    
}
