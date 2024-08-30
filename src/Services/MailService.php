<?php
namespace App\Services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailService
{
    private MailerInterface $mailer;
    private LoggerInterface $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function sendMail(string $from, string $to, string $subject, array $message): void
    {
        $email = (new TemplatedEmail())
        ->from($from)
        ->to($to)
        ->subject($subject)
        ->htmlTemplate('mail/contact.html.twig')
        ->context([
            'name' => $message['name'],
            'phone' => $message['phone'],
            'user_email' => $message['email'], 
            'message' => $message['message'],
        ]);
        $this->mailer->send($email);
    }
}
