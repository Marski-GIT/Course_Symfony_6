<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\{Post, User};
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewPostListener
{
    private MailerInterface $mailer;

    /**
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    /**
     * @throws TransportExceptionInterface
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Post) {
            return;
        }

        $entityManager = $args->getObjectManager();
        $users = $entityManager->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            $email = (new Email())
                ->from('post@symfony.com')
                ->to($user->getEmail())
                ->subject('New post from ' . $entity->getUser()->getName())
                ->html('<p>See new post! ' . $entity->getTitle() . '</p>');

            $this->mailer->send($email);
        }
    }
}