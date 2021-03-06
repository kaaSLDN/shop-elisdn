<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 11.10.17
 * Time: 16:55
 */
namespace shop\services\auth;

use shop\entities\User;
use shop\forms\auth\SignupForm;
use yii\mail\MailerInterface;

class SignupService
{
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function signup(SignupForm $form): User
    {

        $user = User::requestSignup(
          $form->username,
          $form->email,
          $form->password
        );

        $this->save($user);

        $sent = $this->mailer
            ->compose(
                ['html' => 'emailConfirmToken-html', 'text' => 'emailConfirmToken-text'],
                ['user' => $user]
            )
            ->setTo($form->email)
            ->setSubject('Signup confirm for ' . \Yii::$app->name)
            ->send();
        if (!$sent) {
             throw new \runtimeException('Email sending error.');
        }

    }

    public function confirm($token): void
    {
        if (empty($token)) {
            throw new \DomainException('Empty confirm token.');
        }
        $user = $this->getByEmailConfirmToken($token);
        $user->confirmSignup();
        $this->save($user);
    }

    private function getByEmailConfirmToken(string $token): User
    {
        if (!$user = User::findOne(['email_confirm_token' => $token])) {
            throw new \DomainException('User is not found.');
        }
        return $user;
    }

    private function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

     

}

