<?php

namespace App\Twig\Components;

use App\Entity\Post;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\{LiveAction, LiveProp};

#[AsLiveComponent('LikeComponent')]
final class LikeComponent
{
    use DefaultActionTrait;

    public Post $post;
    
    #[LiveProp(writable: true)]
    public int $likes = 0;

    #[LiveProp(writable: true)]
    public int $dislikes = 0;

    #[LiveAction]
    public function like()
    {
        $this->likes++;
    }

    #[LiveAction]
    public function undoLike()
    {
        $this->likes--;
    }

    #[LiveAction]
    public function dislike()
    {
        $this->dislikes++;
    }

    #[LiveAction]
    public function undoDislike()
    {
        $this->dislikes--;
    }

}
