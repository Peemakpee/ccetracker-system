<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deadline;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SetDeadlinesController extends Controller
{
    public function storeDeadlines(Request $request)
    {
        $validatedData = $request->validate([
            'program' => 'required|string',
            'deadline' => 'required|date',
            'deliverable_type' => 'required|string',
        ]);

        $deadline = Deadline::create($validatedData);

        // $facultyEmails = $this->getFacultyEmails($request->input('program'));
        // $data = [
        //     'program' => $request->input('program'),
        //     'deadline' => $request->input('deadline'),
        //     'deliverable_type' => $request->input('deliverable_type'),
        // ];

        // foreach ($facultyEmails as $facultyEmail) {

        //     Mail::to($facultyEmail)->send(new \App\Mail\SetDeadlines($data));

        // }

        return response()->json([
            'message' => 'Deadline successfully set!',
            'data' => $deadline
        ], 201);
    }

    private function getFacultyEmails($program)
    {
        return User::where([
            ['is_admin', '=', false],
            ['program', '=', $program],
            ['status', '=', 'active'],
        ])->pluck('email')->toArray();
    }

    // private function getFacultyEmails($program)
    // {

    //     return User::where([
    //         ['is_admin', '=', false], 
    //         ['program', '=', $program], 
    //     ])->pluck('email')->toArray();
    // }

    public function sendReminder($userId, $program, $deliverableType)
    {
        // $user = User::find($userId);

        // if ($user) {
        //     $deadline = Deadline::where('program', $program)
        //         ->where('deliverable_type', $deliverableType)
        //         ->first();

        //     if ($deadline) {
        //         $followUpData = [
        //             'program' => $program,
        //             'deadline' => $deadline->deadline, 
        //             'deliverable_type' => $deliverableType,
        //         ];

        //         Mail::to($user->email)->send(new \App\Mail\FollowUpReminder($followUpData));
        //     }
        // }

        return response()->json(['message' => 'Reminder sent successfully.']);
    }

    public function getDeadlinesByProgram($program)
    {
        $deadlines = Deadline::where('program', $program)->get();

        if ($deadlines->isEmpty()) {
            return response()->json(['message' => 'Deadlines not found for the specified program'], 404);
        }

        return response()->json($deadlines);
    }

    public function getDeadlinesUpload($id)
    {
        $upload = Deadline::find($id);
        if (!$upload) {
            return response()->json(['message' => 'Document not found'], 404);
        }
        return response()->json($upload);
    }

    public function getFacultyCompliance()
    {
        $allData = Deadline::all();
        return response()->json($allData);
    }

    public function deleteFacultyCompliance($id)
    {
        $upload = Deadline::find($id);
        if (!$upload) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $upload->delete();
        return response()->json(['message' => 'Successfully deleted']);
    }


    public function getProgramCount($program)
    {
        $userCount = DB::table('users')
            ->where('program', $program)
            ->where(function ($query) {
                $query->where('is_admin', false)
                    ->orWhereNull('is_admin');
            })
            ->count();

        return $userCount;
    }

    public function getFacultyComplianceProgram($program)
    {

        if ($program === 'dean') {
            $deadlines = Deadline::all();
        } else {
            $deadlines = Deadline::where('program', $program)->get();
        }

        if ($deadlines->isEmpty()) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        return response()->json($deadlines);
    }
}
