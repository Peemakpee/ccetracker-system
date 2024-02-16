<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TosTemp;

class TosTempController extends Controller
{
    public function storeTOStemp(Request $request)
    {
        $data = $request->validate([
            'customMetadata.deliv_type' => 'required',
            'customMetadata.file_name' => 'required',
            'customMetadata.file_type' => 'required',
            'customMetadata.file_size' => 'required',
        ]);

        $metadata = TosTemp::create([
            'deliv_type' => $data['deliv_type'],
            'file_name' => $data['file_name'],
            'file_path' => $data['file_path'],
            'file_type' => $data['file_type'],
            'file_size' => $data['file_size'],
        ]);

        return response([
            'message' => 'Metadata stored successfully',
            'metadata' => $metadata

        ]);
    }
}
