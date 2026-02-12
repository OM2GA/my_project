<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailNotifier
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendWelcomeEmail(string $recipientEmail): void
    {
        $email = (new Email())
            ->from('no-reply@mon-api.com')
            ->to($recipientEmail)
            ->subject('Bienvenue sur notre API !')
            ->text('Bonjour, votre compte a bien été créé. Bienvenue parmi nous !');

        $this->mailer->send($email);
    }
}