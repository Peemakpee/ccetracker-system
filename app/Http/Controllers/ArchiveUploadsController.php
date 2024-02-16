<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ArchiveUploads;

class ArchiveUploadsController extends Controller
{
    public function getArchive()
    {
        try {

            $archive = ArchiveUploads::all();

            return response()->json($archive);
        } catch (\Exception $e) {

            Log::error('Error fetching archive data: ' . $e->getMessage());

            return response()->json(['error' => 'An error occurred while fetching the archive changes.'], 500);
        }
    }

    public function getArchiveProgram($program)
    {
        if ($program === 'dean') {
            $uploads = ArchiveUploads::where('program', $program)->get();
        } else {
            $uploads = ArchiveUploads::where('program', $program)->get();
        }

        if ($uploads->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'No documents found']);
        }

        return response()->json($uploads);
    }

    public function deleteArchive($id)
    {
        $upload = ArchiveUploads::find($id);
        if (!$upload) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $upload->delete();
        return response()->json(['message' => 'Successfully deleted']);
    }
}
