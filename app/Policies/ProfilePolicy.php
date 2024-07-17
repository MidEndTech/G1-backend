<?php

namespace App\Policies;

use App\Models\User;

class ProfilePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function show(User $user, User $model)
    {

        if( $user->id === $model->id) {
            throw new \App\Exceptions\UnauthorizedActionException('المستخدم لم يسجل دخوله, يرجى تسجيل الدخول لعرض صفحة المستخدم');
        }
    
        return true;
    }

    public function update(User $user, User $model)
    {

    if( $user->id === $model->id) {
        throw new \App\Exceptions\UnauthorizedActionException('المستخدم لم يسجل دخوله, يرجى تسجيل الدخول لتعديل معلومات المستخدم');
    }

    return true;
}

}
