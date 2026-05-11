<?php

namespace App\Twig\Components;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Sidebar
{
    public string $currentRoute = '';

    public function __construct(RequestStack $requestStack)
    {
        $this->currentRoute =
            $requestStack->getCurrentRequest()?->attributes->get('_route') ?? '';
    }

    public function isCurrent(string $route): bool
    {
        return $this->currentRoute === $route;
    }
}
