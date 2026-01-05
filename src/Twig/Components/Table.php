<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Table
{
    /** @var string[] */
    public array $columns = [];

    /** @var iterable */
    public iterable $rows = [];

    /** @var string[] */
    public array $fields = [];

    public bool $actions = false;

    public string $class = '';

    public string $emptyMessage = 'Aucune donnée';
}
