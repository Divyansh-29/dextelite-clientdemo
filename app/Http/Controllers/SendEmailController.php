<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Lead;

class SendEmailController extends Controller
{
    public function handle(Request $request)
    {
        if (!$request->isMethod('post')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid method',
            ], 405);
        }

        $type    = trim($request->input('type', ''));
        $payload = $request->input('payload', '{}');

        $data = json_decode($payload, true);
        if (!is_array($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payload JSON',
            ], 400);
        }

        // common fields
        $name    = trim($data['name']    ?? '');
        $phone   = trim($data['phone']   ?? '');
        $email   = trim($data['email']   ?? '');
        $comment = trim($data['comment'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or missing email',
            ], 400);
        }

        $adminEmail = config('mail.from.address');
        $fromEmail  = config('mail.from.address');

        $adminSubject = '';
        $adminBody    = '';
        $userSubject  = '';
        $userBody     = '';

        // values for DB
        $scheduleDate = null;
        $scheduleTime = null;

        if ($type === 'lead') {
            // Admin email
            $adminSubject = 'New Consultation Lead from Landing Page';
            $adminBody  = "New consultation lead:\n\n";
            $adminBody .= "Name: {$name}\n";
            $adminBody .= "Phone: {$phone}\n";
            $adminBody .= "Email: {$email}\n";
            $adminBody .= "Comment: {$comment}\n";

            // User email
            $userSubject = 'Thanks for requesting the free training';
            $userBody  = "Hi {$name},\n\n";
            $userBody .= "Thank you for requesting access to the free training.\n\n";
            $userBody .= "We received:\n";
            $userBody .= "Name: {$name}\n";
            $userBody .= "Phone: {$phone}\n";
            $userBody .= "Email: {$email}\n";
            $userBody .= "Comment: {$comment}\n\n";
            $userBody .= "We’ll email you the training link shortly.\n\n";
            $userBody .= "Best regards,\nDextelite\n";

        } elseif ($type === 'schedule') {
            $date     = trim($data['date']     ?? '');
            $timeSlot = trim($data['timeSlot'] ?? '');

            $scheduleDate = $date ?: null;
            $scheduleTime = $timeSlot ?: null;

            // Admin email
            $adminSubject = 'New Consultation Schedule Request';
            $adminBody  = "New consultation schedule request:\n\n";
            $adminBody .= "Name: {$name}\n";
            $adminBody .= "Phone: {$phone}\n";
            $adminBody .= "Email: {$email}\n";
            $adminBody .= "Comment: {$comment}\n\n";
            $adminBody .= "Preferred Date: {$date}\n";
            $adminBody .= "Preferred Time Slot: {$timeSlot}\n";

            // User email
            $userSubject = 'Your consultation schedule request';
            $userBody  = "Hi {$name},\n\n";
            $userBody .= "Thanks for booking a consultation.\n\n";
            $userBody .= "Preferred Date: {$date}\n";
            $userBody .= "Preferred Time Slot: {$timeSlot}\n\n";
            $userBody .= "We’ll confirm the time and share the meeting link soon.\n\n";
            $userBody .= "Best regards,\nDextelite\n";
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unknown type',
            ], 400);
        }

        // 🔹 Save to DB (this is the important part)
        try {
            Lead::create([
                'name'          => $name,
                'phone'         => $phone,
                'email'         => $email,
                'comment'       => $comment ?: null,
                'schedule_date' => $scheduleDate,
                'schedule_time' => $scheduleTime,
                'type'          => $type,
            ]);
        } catch (\Throwable $e) {
            // If something is wrong with DB, log it so we can see
            \Log::error('Lead save failed: '.$e->getMessage());

            // We still continue to send mail & return JSON,
            // but DB row might not exist.
        }

        try {
            // send to admin
            Mail::raw($adminBody, function ($message) use ($adminEmail, $fromEmail, $adminSubject, $email, $name) {
                $message->from($fromEmail, 'Dextelite');
                $message->to($adminEmail);
                $message->replyTo($email, $name);
                $message->subject($adminSubject);
            });

            // send to user
            Mail::raw($userBody, function ($message) use ($fromEmail, $email, $name, $userSubject, $adminEmail) {
                $message->from($fromEmail, 'Dextelite');
                $message->to($email, $name);
                $message->replyTo($adminEmail, 'Dextelite');
                $message->subject($userSubject);
            });

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            \Log::error('Mail send failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Mail sending failed.',
            ], 500);
        }
    }
}
