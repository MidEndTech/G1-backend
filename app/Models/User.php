<?php

namespace App\Models;

use App\Jobs\SendOtp;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'bio',
        'pic',
        'otp',
        'otp_expires_at',
    ];



    public function generateOtp()
    {
        $this->otp = mt_rand(1000, 9999); // Generate a 6-digit OTP
        $this->otp_expires_at = now()->addMinutes(2); // OTP valid for 2 minutes
        $this->save();
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'otp_expires_at' => 'datetime',

    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }


    public function isOtpValid($otp)
    {
        return $this->otp === $otp && $this->otp_expires_at > now();
    }

    public function clearOtp()
    {
        $this->otp = null;
        $this->otp_expires_at = null;
        $this->save();
    }
}
