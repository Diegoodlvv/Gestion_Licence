<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Select
{
    public string $label = '';

    public string $name = '';

    /** @var array<string, string> */
    public array $options = [];

    public ?string $value = null;

    public bool $required = false;

    public string $placeholder = 'Sélectionnez une option';

    public string $class = '';

    public ?string $error = null;
}
