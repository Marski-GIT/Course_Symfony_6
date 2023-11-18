<?php declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('LikeIconComponent')]
final class LikeIconComponent
{
    public string $class = '';
}
