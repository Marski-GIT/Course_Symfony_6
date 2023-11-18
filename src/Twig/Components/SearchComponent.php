<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

#[AsLiveComponent('SearchComponent')]
final class SearchComponent
{
    use DefaultActionTrait;

    private EntityManagerInterface $entityManager;

    #[LiveProp(writable: true)]
    public string $query = '';

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getPosts(): array
    {
        return $this->query ? $this->entityManager->getRepository(Post::class)->searchPosts($this->query) : [];
    }
}
