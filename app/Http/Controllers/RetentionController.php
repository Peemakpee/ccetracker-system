<?php

namespace App\Http\Controllers;

use App\Models\Retention;
use Illuminate\Http\Request;

class RetentionController extends Controller
{
    public function getRetentionData()
    {
        $retentionData = Retention::all();

        return response()->json($retentionData);
    }

    public function deleteRetention($id)
    {
        try {
            $retention = Retention::findOrFail($id);

            $retention->delete();

            return response()->json(['message' => 'Retention record deleted successfully']);
        } catch (\Exception $e) {
    
            return response()->json(['error' => 'Failed to delete retention record', 'message' => $e->getMessage()], 500);
        }
    }
}
