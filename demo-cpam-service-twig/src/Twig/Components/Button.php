<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Button
{
    public string $label = 'Button';
    public ?string $href = null;
    public string $class = 'btn-primary';
    public string $type = 'button';
    public ?string $turbo_frame = null;
    public ?string $action = null;
    public array $data = [];


}
