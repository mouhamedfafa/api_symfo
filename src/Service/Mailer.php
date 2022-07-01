<?php

namespace App\Service;

use App\Entity\User;
use Twig\Environment;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class Mailer {
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendEmail($user)
    {

        $email = (new Email())
        ->from('hello@example.com')
        ->to($user -> getLogin())
        ->subject('Time for Symfony Mailer!')
        ->text('Sending emails is fun again!')
        ->html($this->twig->render('mailer/index.html.twig'));

        $this->mailer->send($email);  

    // ...
    }
}