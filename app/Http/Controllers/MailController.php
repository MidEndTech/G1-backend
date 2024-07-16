<?php

namespace App\Http\Controllers;
use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'emails' => 'required|array',
            'emails.*' => 'email',
        ]);

        $data = [
            'message' => 'This is a test message sent via API to multiple recipients.',
        ];

        // Initialize an array to collect all recipients
        $recipients = [];

        foreach ($request->emails as $email) {
            // Add each email to the recipients array
            $recipients[] = $email;
        }

        // Send the email with recipients added to the 'to' field
        Mail::to($recipients)->send(new TestMail($data));

        return response()->json(['message' => 'Emails sent successfully'], 200);
    }
}
