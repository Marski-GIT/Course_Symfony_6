<?php

namespace App\Twig\Components;

use App\Entity\Post;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent()]
final class PostDetailsComponent
{
    public array $isFollowing;
    public Post $post;
}
