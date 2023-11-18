<?php declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('UnlikeIconComponent')]
final class UnlikeIconComponent
{
    public string $class = '';
}
