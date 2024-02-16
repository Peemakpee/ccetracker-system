<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DeliverableTemplates;
use Illuminate\Support\Facades\Mail;

class DeliverableTemplatesController extends Controller
{
    public function uploadTemplates(Request $request)
    {
        Log::debug('Store method was called');

        $request->validate([
            'type' => 'required|string',
            'firebase_url' => 'required|string',
        ]);

        $uploadFile = DeliverableTemplates::create($request->all());

        // $programHeadEmail = $this->getProgramHeadEmail($request->input('program'));
        // $data = [
        //     'user_name' => $request->input('user_name'),
        //     'program' => $request->input('program'),
        //     'file_name' => $request->input('file_name'),
        //     'deliverable_type' => $request->input('deliverable_type'),
        // ];

        // Mail::to($programHeadEmail)->send(new \App\Mail\DeliverableUploaded($data)); 

        return response()->json($uploadFile, 201);
    }

    public function getTemplates()
    {
        $templates = DeliverableTemplates::all();
        return response()->json($templates);
    }

    public function deleteTemplate($type)
    {
        try {
            $templates = DeliverableTemplates::where('type', $type)->get();

            if ($templates->isEmpty()) {
                return response()->json(['message' => 'No templates found with the specified type'], 404);
            }

            $templates->each(function ($template) {
                $template->delete();
            });

            return response()->json(['message' => 'Templates deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting templates'], 500);
        }
    }

    public function getTemplateById($id)
    {

        $template = DeliverableTemplates::find($id);

        if (!$template) {
            return response()->json(['error' => 'Template not found'], 404);
        }

        return response()->json($template);
    }

    public function getTemplatesUser(Request $request)
    {
        $templates = DeliverableTemplates::all();

        return response()->json($templates);
    }

    public function getDetailedTemplates($id)
    {
        $template = DeliverableTemplates::find($id);

        if (!$template) {
            return response()->json(['error' => 'Template not found'], 404);
        }

        return response()->json([$template]);
    }

    public function getTemplatesOptions()
    {
        try {
            $types = DeliverableTemplates::select('type')
                ->groupBy('type')
                ->orderBy('created_at')
                ->pluck('type');

            return response()->json($types, 200);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Error fetching deliverable types.'], 500);
        }
    }

    public function getUniqueDeliverableTypes()
    {
        $types = DeliverableTemplates::distinct()->pluck('type');
        return response()->json($types);
    }

    public function getDeliverableTypes()
    {
        $types = DeliverableTemplates::distinct()->pluck('type');
        Log::debug('Deliverable Types:', $types);
        return response()->json($types);
    }

   
}
