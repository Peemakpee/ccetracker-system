<?php

namespace App\Http\Controllers;

use App\Models\ApprovedPh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ReuploadDean;
use App\Models\TbmDean;
use App\Models\User;
use App\Models\UploadFile;
use Illuminate\Support\Facades\Mail;

class ReuploadDeanController extends Controller
{

    public function storeReuploadDean(Request $request)
    {
        try {
            Log::info('Reupload_dean Request Data:', $request->all());

            $request->validate([
                'document_id' => 'required|integer',
                'file_path' => 'required|string',
                'academic_year' => 'required|string',
                'user_name' => 'required|string',
                'date_uploadedByUser' => 'required|date',
                'user_id' => 'required|integer',
                'deliverable_type' => 'required|string',
                'file_name' => 'required|string',
                'firebaseUrl_wSign' => 'required|url',
                'program' => 'required|string',
            ]);

            $date = new \DateTime($request->input('date_uploadedByUser'));
            $request->merge(['date_uploadedByUser' => $date->format('Y-m-d H:i:s')]);

            $reupload = ReuploadDean::create($request->all());

            Log::info('Reupload_dean Created:', [$reupload]);

            TbmDean::where('document_id', $request->input('document_id'))->delete();

            // $deanEmail = $this->getDeanEmail();       
            // $mailData = [
            //     'user_name' => $request->input('user_name'),
            //     'program' => $request->input('program'),
            //     'file_name' => $request->input('file_name'),
            //     'deliverable_type' => $request->input('deliverable_type'),
            // ];

            // Mail::to($deanEmail)->send(new \App\Mail\ReuploadedFileDean($mailData));

            return response()->json($reupload, 201);
        } catch (\Exception $e) {

            Log::error('Error in storeReuploadDean:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return response()->json(['error' => 'Failed to reupload_dean file'], 500);
        }
    }

    private function getDeanEmail()
    {
        $deanUser = User::where('is_dean', 1)->first();

        return $deanUser ? $deanUser->email : null;
    }

    public function getReuploadDean()
    {
        $reuploads = ReuploadDean::whereNull('status')->get();

        if ($reuploads->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'No reuploaded documents found']);
        }

        return response()->json($reuploads);
    }

    public function getReuploadedDeanById($id)
    {
        $reupload = ReuploadDean::where('document_id', $id)->first();

        if (!$reupload) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        return response()->json(['data' => $reupload]);
    }

    public function updateReuploadDeanStatus(Request $request)
    {
        try {
            $documentId = $request->input('documentId');
            $status = $request->input('status');

            DB::table('reupload_dean')
                ->where('document_id', $documentId)
                ->update(['status' => $status]);

            $columnMapping = [
                'file_name' => 'file_name',
                'file_path' => 'file_path',
                'firebaseUrl_wSign' => 'firebaseUrl_wSign',
                'program' => 'program',
                'academic_year' => 'academic_year',
                'user_id' => 'user_id',
                'user_name' => 'user_name',
                'created_at' => 'created_at',
                'updated_at' => 'updated_at',
                'deliverable_type' => 'deliverable_type',
                'status' => 'status',
                'deadline_id' => 'deadline_id',
                'subject' => 'subject',
                'subject_code' => 'subject_code',
            ];

            $reuploadDeanEntry = DB::table('reupload_dean')
                ->where('document_id', $documentId)
                ->first();

            if ($reuploadDeanEntry) {
                $uploadFileData = [];
                foreach ($columnMapping as $uploadFileColumn => $reuploadDeanColumn) {
                    $uploadFileData[$uploadFileColumn] = $reuploadDeanEntry->$reuploadDeanColumn;
                }

                ApprovedPh::updateOrCreate(['document_id' => $documentId], $uploadFileData);
            }

            DB::table('reupload_dean')->where('document_id', $documentId)->delete();

            return response()->json(['message' => 'Reupload Files Status Updated and Transferred']);
        } catch (\Exception $e) {
            Log::error('Error updating reupload Dean status: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update and transfer Reupload Files Status'], 500);
        }
    }
}
