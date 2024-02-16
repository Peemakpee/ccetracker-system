<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\ApprovedPh;
use App\Models\ArchiveUploads;
use App\Models\ReuploadDean;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;

class ApprovePhController extends Controller
{
    public function storeApprovedPH(Request $request)
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
            'academic_year' => 'required|string',
            'program' => 'required|string',
            'user_id' => 'required|integer',
            'user_name' => 'required|string',
        ]);

        $uploadApprovedPh = ApprovedPh::create($request->all());

        // $deanEmail = $this->getDeanEmail();       
        // $approvedPhdata = [
        //     'user_name' => $request->input('user_name'),
        //     'program' => $request->input('program'),
        //     'file_name' => $request->input('file_name'),
        //     'deliverable_type' => $request->input('deliverable_type'),
        // ];

        // Mail::to($deanEmail)->send(new \App\Mail\ApprovedByPh($approvedPhdata));

        return response()->json($uploadApprovedPh, 201);
    }

    private function getDeanEmail()
    {
        $deanUser = User::where('is_dean', 1)->first();

        return $deanUser ? $deanUser->email : null;
    }

    public function getApprovedPh()
    {
        $approvedPh = ApprovedPh::whereNull('status')->get();

        if ($approvedPh->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'No approved documents found']);
        }

        return response()->json($approvedPh);
    }


    public function getApprovedPhId($id)
    {
        $upload = ApprovedPh::find($id);
        if (!$upload) {
            return response()->json(['message' => 'Document not found'], 404);
        }
        return response()->json($upload);
    }

    public function updateApproveStatus(Request $request)
    {
        try {
            $documentId = $request->input('documentId');
            $status = $request->input('status');

            DB::table('approve_ph')
                ->where('document_id', $documentId)
                ->update(['status' => $status]);

            return response()->json(['message' => 'Approve PH Status Updated']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Approve PH Status'], 500);
        }
    }


    public function getApprovedFiles($userId)
    {
        $userFiles = ApprovedPh::where('user_Id', $userId)->get();
        return response()->json($userFiles);
    }

    public function archiveApprovedPh(Request $request, $id)
    {
        $source = $request->input('source');

        $upload = null;
        $archiveStatus = '';

        if ($source === 'phase2') {
            $upload = ApprovedPh::find($id);
            $archiveStatus = 'approve_ph';
        } elseif ($source === 'reuploadDean') {
            $upload = ReuploadDean::where('document_id', $id)->first();
            $archiveStatus = 'reupload_dean';
        }

        if (!$upload) {
            return response()->json(['message' => 'Upload not found'], 404);
        }

        $archiveUpload = new ArchiveUploads();
        $archiveUpload->document_id = ($source === 'phase2') ? $upload->id : $upload->document_id;
        $archiveUpload->status = $archiveStatus;
        $archiveUpload->file_name = $upload->file_name;
        $archiveUpload->firebase_url = $upload->firebaseUrl_wSign;
        $archiveUpload->program = $upload->program;
        $archiveUpload->user_id = $upload->user_id;
        $archiveUpload->user_name = $upload->user_name;
        $archiveUpload->academic_year = $upload->academic_year;
        $archiveUpload->date_uploadedByUser = $upload->created_at;
        $archiveUpload->deliverable_type = $upload->deliverable_type;

        $archiveUpload->save();

        $upload->delete();

        return response()->json(['message' => 'Successfully archived']);
    }

    public function getDeliverablePhCounts(Request $request)
    {
        try {
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');
            $academicYear = $request->input('academicYear');

            $query = DB::table('approve_ph')
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
            return response()->json(['message' => 'Failed to fetch deliverable PH counts'], 500);
        }
    }
}
