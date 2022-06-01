<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

abstract class Tag implements Renderable
{
    protected static array $reservedAttributes = [
        'tag',
        'inner',
        'attributesPipeline',
    ];

    public string $tag;

    public array $attributesPipeline = [];

    public function render(): View
    {
        return view("seo::tags.tag", [
            'tag' => $this->tag,
            'attributes' => $this->collectAttributes(),
            'inner' => $this->inner ?? null,
        ]);
    }

    public function collectAttributes(): Collection
    {
        return collect($this->attributes ?? get_object_vars($this))
            ->except(static::$reservedAttributes)
            ->pipeThrough($this->attributesPipeline);
    }
}