<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Str;

trait RenderableCollection
{
    public function render(): string
    {
        return $this->reduce(function (string $carry, Renderable $item): string {
            return $carry .= Str::of(
                $item->render()
            )->trim()->trim(PHP_EOL);
        }, '');
    }
}