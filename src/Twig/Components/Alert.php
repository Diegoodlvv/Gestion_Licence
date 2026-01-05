<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Alert
{
    /**
     * success | error | warning
     */
    public string $variant = 'success';

    public string $class = '';

    public bool $dismissible = false;
}
