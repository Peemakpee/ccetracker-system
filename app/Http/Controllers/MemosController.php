<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memos;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MemosController extends Controller
{
    public function uploadMemo(Request $request)
    {
        $request->validate([
            'memo_to' => 'required|string',
            'memo_from' => 'required|string',
            'memo_subject' => 'required|string',
            'firebase_url' => 'required|url',
        ]);

        $uploadMemos = Memos::create($request->all());

        // $recipientEmails = $this->getRecipientEmails($request->input('memo_to'));
        // $data = [
        //     'memo_to' => $request->input('memo_to'),
        //     'memo_from' => $request->input('memo_from'),
        //     'memo_subject' => $request->input('memo_subject'),
        //     'created_at' => $request->input('created_at'),
        // ];

        // foreach ($recipientEmails as $recipientEmail) {
        //     sleep(1);
        //     Mail::to($recipientEmail)->send(new \App\Mail\MemoNotification($data));
        // }

        return response()->json($uploadMemos, 201);
    }

    private function getRecipientEmails($recipientType)
    {
        if ($recipientType == 'All PH') {
            
            return User::where([
                ['is_admin', '=', true],
                ['is_aa', '=', false],
                ['is_dean', '=', false]
            ])->pluck('email')->toArray();
        } elseif ($recipientType == 'All Faculty') {
         
            return User::where([
                ['is_admin', '=', false],
                ['is_aa', '=', false],
                ['is_dean', '=', false]
            ])->pluck('email')->toArray();
        } elseif ($recipientType != 'Individual') {
           
            return User::where('name', $recipientType)->pluck('email')->toArray(); 
        }

        return [];
    }


    public function getMemosByRecipient($recipientName)
    {
        try {
            $memos = Memos::where('memo_to', $recipientName)
                ->orWhere('memo_to', 'All Faculty')
                ->get();

            return response()->json($memos);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch memos'], 500);
        }
    }


    public function getMemosByPH($phId)
    {
        try {
            $selectedDate = Carbon::parse(request('date'))->format('Y-m-d');

            $programHead = User::find($phId);

            if (!$programHead) {
                return response()->json(['message' => 'Program Head not found'], 404);
            }

            $programHeadName = $programHead->name;
            $memos = Memos::where(function ($query) use ($programHeadName) {
                $query->where('memo_to', $programHeadName)
                    ->orWhere('memo_to', 'All PH');
            })
                ->when($selectedDate, function ($query) use ($selectedDate) {
                    $query->whereDate('created_at', $selectedDate);
                })
                ->get();

            return response()->json($memos);
        } catch (\Exception $e) {
            Log::error($e); 
            return response()->json(['message' => 'Failed to fetch memos'], 500);
        }
    }


    public function deleteMemo($id)
    {
        $upload = Memos::find($id);
        if (!$upload) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $upload->delete();
        return response()->json(['message' => 'Successfully deleted']);
    }

    public function getDetailedMemo($id)
    {
        $upload = Memos::find($id);
        if (!$upload) {
            return response()->json(['message' => 'Document not found'], 404);
        }
        return response()->json($upload);
    }

    public function deleteUserMemo($id)
    {
        try {
            $memo = Memos::findOrFail($id);
            $memo->delete();

            return response()->json(['message' => 'Memo deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete memo'], 500);
        }
    }
}
