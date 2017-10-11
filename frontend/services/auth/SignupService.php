<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 11.10.17
 * Time: 16:55
 */
namespace frontend\services\auth;

use common\entities\User;
use frontend\forms\SignupForm;

class SignupService
{
    public function signup(SignupForm $form): User
    {
        $user = User::signup(
          $form->username,
          $form->email,
          $form->password
        );

        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }

        return $user;
    }
}
