<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

final class AuthLayout extends Component
{
    public function __construct(
        public string $title = 'Iniciar sesión',
        public ?string $subtitle = null,
    ) {}

    public function render(): View
    {
        return view('layouts.auth');
    }
}
