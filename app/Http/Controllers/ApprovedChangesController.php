<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovedChanges;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\ArchiveUploads;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApprovedChangesController extends Controller
{
    public function storeApprovedChanges(Request $request)
    {
        Log::debug('Store method was called');
        $request->validate([
            'document_id' => 'required|integer',
            'change_description' => 'nullable|string',
            'file_path' => 'nullable|string',
            'firebaseUrl_changes' => 'required|url',
            'date_upladedByUser' => 'required|date',
            'deliverable_type' => 'required|string',
            'file_name' => 'required|string',
            'status' => 'required|string',
            'academic_year' => 'required|string',
            'program' => 'required|string',
            'user_id' => 'required|integer',
            'user_name' => 'required|string',
        ]);

        $uploadApprovedChanges = ApprovedChanges::create($request->all());

        // $facultyEmail = $this->getFacultyEmail($request->input('user_name'));
        // $approvedDeandata = [
        //     'user_name' => $request->input('user_name'),
        //     'file_name' => $request->input('file_name'),
        //     'deliverable_type' => $request->input('deliverable_type'),
        //     // 'approved_by' => $request ->input('approved_by')
        // ];

        // Mail::to($facultyEmail)->send(new \App\Mail\ApprovedChanges($approvedDeandata));

        return response()->json($uploadApprovedChanges, 201);
    }

    private function getFacultyEmail($userName)
    {
        $facultyUser = User::where([
            ['name', '=', $userName],
            ['is_admin', '=', false]
        ])->first();

        return $facultyUser ? $facultyUser->email : null;
    }

    public function getDetailedApprovedChanges($docId)
    {

        $userFiles = ApprovedChanges::where('document_id', $docId)->get();
        return response()->json($userFiles);
    }

    public function getApprovedChangesFiles($userId)
    {
        $userFiles = ApprovedChanges::where('user_Id', $userId)->get();
        return response()->json($userFiles);
    }

    public function getDetailedMemo($id)
    {
        $upload = ApprovedChanges::find($id);
        if (!$upload) {
            return response()->json(['message' => 'Document not found'], 404);
        }
        return response()->json($upload);
    }

    public function getApprovedChanges()
    {
        try {
           
            $approvedChanges = ApprovedChanges::all();

         
            return response()->json($approvedChanges);
        } catch (\Exception $e) {
         
            Log::error('Error fetching approved changes: ' . $e->getMessage());

        
            return response()->json(['error' => 'An error occurred while fetching the approved changes.'], 500);
        }
    }

    public function getApprovedChangesProgram($program)
    {
        if ($program === 'dean') {
            $uploads = ApprovedChanges::where('program', $program)->get();
        } else {
            $uploads = ApprovedChanges::where('program', $program)->get();
        }

        if ($uploads->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'No documents found']);
        }

        return response()->json($uploads);
    }

    public function archiveApprovedChanges($id)
    {
        Log::info('Received archive request for ID: ' . $id);
        $upload = ApprovedChanges::where('document_id', $id)->first();

        if (!$upload) {
            return response()->json(['message' => 'Approved File by PH not found'], 404);
        }

        $archiveUpload = new ArchiveUploads();
        $archiveUpload->document_id = $upload->document_id;
        $archiveUpload->status = 'approve_changes';
        $archiveUpload->file_name = $upload->file_name;
        $archiveUpload->firebase_url = $upload->firebaseUrl_changes;
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

    public function getApproveChangesCounts(Request $request)
    {
        try {
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');
            $academicYear = $request->input('academicYear');

            $query = DB::table('approved_changes')
                ->select('program', DB::raw('count(*) as count'))
                ->groupBy('program');

            if (!empty($academicYear)) {
                $query->where('academic_year', $academicYear);
            }

            if (!empty($fromDate) && !empty($toDate)) {
                $startOfDay = Carbon::parse($fromDate)->startOfDay();
                $endOfDay = Carbon::parse($toDate)->endOfDay();
                $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
            }

            $counts = $query->get();

            return response()->json($counts);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch approved changes counts'], 500);
        }
    }
}
