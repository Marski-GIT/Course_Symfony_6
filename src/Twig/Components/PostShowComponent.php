<?php

namespace App\Twig\Components;

use App\Entity\Post;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent()]
final class PostShowComponent
{
    public Post $post;
    public array $isFollowing;
    public array $isLiked;
    public array $isDisLiked;
}
