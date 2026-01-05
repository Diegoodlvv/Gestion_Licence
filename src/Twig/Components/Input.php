<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Input
{
    public string $type = 'text';

    public string $label = '';

    public string $name = '';

    public ?string $value = null;

    public string $placeholder = '';

    public bool $required = false;

    public string $class = '';

    public ?string $error = null;
}
