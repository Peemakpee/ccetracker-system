<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\DeliverableSubmissionNotification;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
      
        $email = $request->input('email');
        $fileURL = $request->input('fileURL');
 
        Notification::route('mail', $email)->notify(new DeliverableSubmissionNotification($fileURL));

        return response()->json(['message' => 'Notification sent successfully']);
    }
}
