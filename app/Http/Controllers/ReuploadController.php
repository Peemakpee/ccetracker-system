<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reupload;
use App\Models\UploadFile;
use Illuminate\Support\Facades\Log;
use App\Models\ArchiveUploads;
use App\Models\TbmPh;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ReuploadController extends Controller
{
    public function storeReuploads(Request $request)
    {
        try {
            Log::info('Reupload Request Data:', $request->all());

            $request->validate([
                'document_id' => 'required|integer',
                'file_path' => 'required|string',
                'academic_year' => 'required|string',
                'user_name' => 'required|string',
                'date_uploadedByUser' => 'required|date',
                'user_id' => 'required|integer',
                'deliverable_type' => 'required|string',
                'file_name' => 'required|string',
                'firebase_url' => 'required|url',
                'program' => 'required|string',
            ]);

            $date = new \DateTime($request->input('date_uploadedByUser'));
            $request->merge(['date_uploadedByUser' => $date->format('Y-m-d H:i:s')]);

            $reupload = Reupload::create($request->all());

            Log::info('Reupload Created:', [$reupload]);

            TbmPh::where('document_id', $request->input('document_id'))->delete();

            // $programHeadEmail = $this->getProgramHeadEmail($request->input('program'));
            // $data = [
            //     'user_name' => $request->input('user_name'),
            //     'program' => $request->input('program'),
            //     'file_name' => $request->input('file_name'),
            //     'deliverable_type' => $request->input('deliverable_type'),
            // ];

            // Mail::to($programHeadEmail)->send(new \App\Mail\ReuploadedFileNotification($data));

            return response()->json($reupload, 201);
        } catch (\Exception $e) {

            Log::error('Error in storeReuploads:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return response()->json(['error' => 'Failed to reupload file'], 500);
        }
    }

    private function getProgramHeadEmail($programName)
    {
        $programHead = User::where([
            ['program', '=', $programName],
            ['is_admin', '=', true]
        ])->first();

        return $programHead ? $programHead->email : null;
    }

    public function getReuploadsProgram($program)
    {
        if ($program === 'dean') {
            $reuploads = Reupload::whereNull('status')->get();
        } else {
            $reuploads = Reupload::where('program', $program)->whereNull('status')->get();
        }

        if ($reuploads->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'No documents found']);
        }

        return response()->json($reuploads);
    }

    public function getReuploads()
    {
        $reuploads = Reupload::whereNull('status')->get();

        if ($reuploads->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'No approved documents found']);
        }

        return response()->json($reuploads);
    }

    public function updateReuploadStatus(Request $request)
    {
        try {
            $documentId = $request->input('documentId');
            $status = $request->input('status');

            DB::table('reuploads')
                ->where('document_id', $documentId)
                ->update(['status' => $status]);

            $columnMapping = [
                'file_name' => 'file_name',
                'file_path' => 'file_path',
                'firebase_url' => 'firebase_url',
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

            $reuploadEntry = DB::table('reuploads')
                ->where('document_id', $documentId)
                ->first();

            if ($reuploadEntry) {
                $uploadFileData = [];
                foreach ($columnMapping as $uploadFileColumn => $reuploadColumn) {
                    $uploadFileData[$uploadFileColumn] = $reuploadEntry->$reuploadColumn;
                }

                UploadFile::updateOrCreate(['id' => $documentId], $uploadFileData);
            }
            DB::table('reuploads')->where('document_id', $documentId)->delete();

            return response()->json(['message' => 'Reupload Files Status Updated and Transferred']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update and transfer Reupload Files Status'], 500);
        }
    }

    public function getReuploadedById($id)
    {
        $reupload = Reupload::where('document_id', $id)->first();

        if (!$reupload) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        return response()->json(['data' => $reupload]);
    }
}
