<?php

namespace App\Otp;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SadiqSalau\LaravelOtp\Contracts\OtpInterface as Otp;

class UserRegistrationOtp implements Otp
{
    /**
     * Constructs Otp class
     */
    public function __construct(
        protected string $name,
        protected string $email,
        protected string $password
    ) {
        //
    }

    /**
     * Processes the Otp
     *
     * @return mixed
     */
    public function process()
    {
        $user = User::unguarded(function () {
            return User::create([
                'name'                  => $this->name,
                'email'                 => $this->email,
                'password'              => Hash::make($this->password),
                'email_verified_at'     => now(),
            ]);
        });

        event(new Registered($user));
        
        Auth::login($user);

        return [
            'user' => $user
        ];
    }
    }

