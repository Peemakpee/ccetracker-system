<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileData; // Replace with your actual model name
use Illuminate\Support\Facades\Validator;

class TrackingController extends Controller
{
    public function uploadFiledata(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'selectedProgram' => 'required|string',
            'selectedAcademicYear' => 'required|string',
            'additionalInfo' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $fileData = new FileData();
        $fileData->selectedProgram = $request->input('selectedProgram');
        $fileData->selectedAcademicYear = $request->input('selectedAcademicYear');
        $fileData->additionalInfo = $request->input('additionalInfo');
        $fileData->save();

        return response([
            'message' => 'File data uploaded successfully',
            'data' => $fileData,
        ]);
    }
}
