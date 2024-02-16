<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Replies;
use App\Models\User;

class RepliesController extends Controller
{
    public function sendReply(Request $request)
    {
        $request->validate([
            'message_id' => 'required|integer',
            'reply_to' => 'required|string',
            'reply_from' => 'required|string',
            'reply_message' => 'required|string',
        ]);

        $uploadReply = Replies::create($request->all());

        return response()->json($uploadReply, 201);
    }

    public function getReplyMessage($messageId)
    {
        $replyMessages = Replies::where('message_id', $messageId)->get();
        if ($replyMessages->isEmpty()) {
            return response()->json(['message' => 'No replies found for this message'], 404);
        }
        return response()->json($replyMessages);
    }

    
}
