<?php

namespace App\Twig\Components;

use App\Entity\Post;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\{LiveAction, LiveProp};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

#[AsLiveComponent('LikeComponent')]
final class LikeComponent
{
    use DefaultActionTrait;

    private EntityManagerInterface $entityManager;
    private Security $security;

    public $isLiked;
    public $isDisliked;

    #[LiveProp(writable: true)]
    public Post $post;

    #[LiveProp(writable: true)]
    public int $likes = 0;

    #[LiveProp(writable: true)]
    public int $dislikes = 0;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    #[LiveAction]
    public function like(): void
    {
        $this->post->addUsersThatLike($this->security->getUser());
        $this->isLiked = true;
        $this->entityManager->persist($this->post);
        $this->entityManager->flush();
    }

    #[LiveAction]
    public function undoLike(): void
    {
        $this->post->removeUsersThatLike($this->security->getUser());
        $this->isLiked = false;
        $this->entityManager->persist($this->post);
        $this->entityManager->flush();
    }

    #[LiveAction]
    public function dislike(): void
    {
        $this->post->addUsersThatDontLike($this->security->getUser());
        $this->isDisliked = true;
        $this->entityManager->persist($this->post);
        $this->entityManager->flush();
    }

    #[LiveAction]
    public function undoDislike(): void
    {
        $this->post->removeUsersThatDontLike($this->security->getUser());
        $this->isDisliked = false;
        $this->entityManager->persist($this->post);
        $this->entityManager->flush();
    }

}
