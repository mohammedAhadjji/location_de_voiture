<?php
namespace App\services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail()
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('mohammed.ahadjji@ump.ac.ma')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        try {
            $this->mailer->send($email);
        } catch (\Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
}
