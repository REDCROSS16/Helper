<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'email', path: '/email')]
class CheckEmailAction extends AbstractController
{
    public function __invoke(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->addTo('belkill@mail.ru')
            ->from('hello@example.com')
            ->text('check mailer')
            ->subject('test');

        try {
            $mailer->send($email);

            return $this->json('sended');
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage());
        }
    }
}
