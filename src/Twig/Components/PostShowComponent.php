<?php

namespace App\Twig\Components;

use App\Entity\Post;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('PostShowComponent')]
final class PostShowComponent
{
    public Post $post;
    public array $isFollowing;
    public $isLiked;
    public $isDisliked;
}
