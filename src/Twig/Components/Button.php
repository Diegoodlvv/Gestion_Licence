<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Button
{
    public string $label = 'Button';

    public string $variant = 'blue';

    public ?string $href = null;

    /**
     * button | submit | reset
     */
    public string $type = 'button';

    public bool $disabled = false;

    public string $class = '';
}
