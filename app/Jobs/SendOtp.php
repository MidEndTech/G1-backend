<?php

namespace App\Jobs;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use SadiqSalau\LaravelOtp\Facades\Otp;

class SendOtp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Execute the job.
     */
    public function handle()
    {
        $user = $this->user;

        // Generate OTP and save to the database
        $otp = mt_rand(1000, 9999); // Generate a 6-digit OTP
        Otp::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(2), // OTP valid for 10 minutes
        ]);

        // Optionally, send OTP to the user via email/SMS
        Mail::to($user->email)->send(new OtpMail($otp));

        Log::info("OTP created for user ID {$user->id}: $otp");
    }
}
