<?php

namespace frontend\services\contact;
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 12.10.17
 * Time: 14:45
 */
use frontend\forms\ContactForm;
use yii\mail\MailerInterface;

class ContactService
{
    private $mailer;
    private $adminEmail;

    public function __construct($adminEmail, MailerInterface $mailer)
    {

        $this->adminEmail = $adminEmail;
        $this->mailer = $mailer;
    }

    public function send(ContactForm $form): void
    {
        $sent = $this->mailer->compose()

            ->setTo($this->adminEmail)
            ->setSubject($form->subject)
            ->setTextBody($form->body)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Sending error.');
        }
    }
}