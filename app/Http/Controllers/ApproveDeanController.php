<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\ApprovedDean;
use App\Models\ArchiveUploads;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApproveDeanController extends Controller
{
    public function storeApprovedDean(Request $request)
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

        $uploadApprovedDean = ApprovedDean::create($request->all());

        // $facultyEmail = $this->getFacultyEmail($request->input('user_name'));
        // $approvedDeandata = [
        //     'user_name' => $request->input('user_name'),
        //     'program' => $request->input('program'),
        //     'file_name' => $request->input('file_name'),
        //     'deliverable_type' => $request->input('deliverable_type'),
        //     // 'approved_by' => $request ->input('approved_by')
        // ];

        // Mail::to($facultyEmail)->send(new \App\Mail\ApprovedByDean($approvedDeandata));

        return response()->json($uploadApprovedDean, 201);
    }

    public function getApprovedDeanFiles($userId)
    {
        $userFiles = ApprovedDean::where('user_Id', $userId)->get();
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

    public function getApprovedByDean()
    {

        $approvedDean = ApprovedDean::whereNull('show')->get();

        if ($approvedDean->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'No approved documents found']);
        }

        return response()->json($approvedDean);
    }

    public function getApprovedByDeanProgram($program)
    {
        if ($program === 'dean') {
            $approvedDeanProgram = ApprovedDean::whereNull('show')->get();
        } else {
            $approvedDeanProgram = ApprovedDean::where('program', $program)->whereNull('show')->get();
        }

        if ($approvedDeanProgram->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'No documents found']);
        }

        return response()->json($approvedDeanProgram);
    }

    public function archiveApprovedDean($id)
    {
        $upload = ApprovedDean::where('document_id', $id)->first();

        if (!$upload) {
            return response()->json(['message' => 'Approved File by PH not found'], 404);
        }

        $archiveUpload = new ArchiveUploads();
        $archiveUpload->document_id = $upload->document_id;
        $archiveUpload->status = 'approve_dean';
        $archiveUpload->file_name = $upload->file_name;
        $archiveUpload->firebase_url = $upload->firebaseUrl_wSignDean;
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

    public function getApprovedDean($id)
    {
        $upload = ApprovedDean::find($id);
        if (!$upload) {
            return response()->json(['message' => 'Document not found'], 404);
        }
        return response()->json($upload);
    }

    public function updateApproveDeanShow($id)
    {
        try {

            $approveDean = ApprovedDean::find($id);

            if (!$approveDean) {
                return response()->json(['message' => 'Record not found'], 404);
            }

            $approveDean->show = true;

            $approveDean->save();

            return response()->json(['message' => 'Show status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating show status'], 500);
        }
    }

    public function getApprovedDeadlines($userId)
    {
        $deadlineIds = ApprovedDean::where('user_id', $userId)->pluck('deadline_id');

        return response()->json($deadlineIds);
    }

    public function getDeliverableDeanCounts(Request $request)
    {
        try {
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');
            $academicYear = $request->input('academicYear');

            $query = DB::table('approve_dean')
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
            return response()->json(['message' => 'Failed to fetch deliverable Dean counts'], 500);
        }
    }
}
