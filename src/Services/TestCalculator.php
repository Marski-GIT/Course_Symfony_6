<?php

declare(strict_types=1);

namespace App\Services;

class TestCalculator
{

    public function add(int $a, int $b): int
    {
        return $a + $b;
    }
}