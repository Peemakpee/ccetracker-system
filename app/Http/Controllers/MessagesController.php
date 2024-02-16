<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Messages;
use App\Models\User;

class MessagesController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_to' => 'required|string',
            'chat_from' => 'required|string',
            'message' => 'required|string',
        ]);

        $uploadMessage = Messages::create($request->all());

        return response()->json($uploadMessage, 201);
    }

    public function getSender($id)
    {
        try {
         
            $user = User::find($id);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $userData = [
                'id' => $user->id,
                'name' => $user->name,
            ];

            return response()->json($userData);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch user'], 500);
        }
    }

    public function getMessages($recipientName)
    {
        try {
            $messages = Messages::where('chat_to', $recipientName)
                ->get();

            return response()->json($messages);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch messages'], 500);
        }
    }

    public function getDetailedMessage($id)
    {
        $message = Messages::find($id);
        if (!$message) {
            return response()->json(['message' => 'message not found'], 404);
        }
        return response()->json($message);
    }

    public function getInbox($adminName)
    {
        try {
            $messages = Messages::where('chat_to', $adminName)
                ->get();

            return response()->json($messages);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch messages'], 500);
        }
    }

    public function deleteInbox($id)
    {
        $upload = Messages::find($id);
        if (!$upload) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $upload->delete();
        return response()->json(['message' => 'Successfully deleted']);
    }

    public function deleteMessage($id)
    {
        $upload = Messages::find($id);
        if (!$upload) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $upload->delete();
        return response()->json(['message' => 'Successfully deleted']);
    }

    public function getInboxMessage($messageId)
    {
        $message = Messages::where('id', $messageId)->get();
        if ($message->isEmpty()) {
            return response()->json(['message' => 'No replies found for this message'], 404);
        }
        return response()->json($message);
    }

    public function getSentMessages($adminName)
    {
        try {
            $messages = Messages::where('chat_from', $adminName)
                ->get();

            return response()->json($messages);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch messages'], 500);
        }
    }
}
