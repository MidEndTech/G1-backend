<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;

class UserNotAuthorizedException extends AuthorizationException
{

    public function __construct($message = "User is not authorized to perform this action.")
    {
        parent::__construct($message);
    }
    public function render($request)
    {
        return response()->json(['error' => $this->getMessage()], 403);
    }
}
