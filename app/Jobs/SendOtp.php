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
        try {
            $otp = $this->user->otp;
            Mail::to($this->user->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            Log::error('Failed to send OTP: ' . $e->getMessage());
            throw $e;
        }
    }
}
