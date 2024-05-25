<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

/**
 * A representation of a HTML tag
 */
abstract class Tag implements Renderable
{
    /**
     * The HTML tag
     */
    public string $tag;

    /**
     * The HTML attributes of the tag
     */
    public array $attributes = [];

    /**
     * The content of the tag
     */
    public null|string|HtmlString $inner = null;

    public array $attributesPipeline = [];

    public function render(): View
    {
        return view('seo::tags.tag', [
            'tag' => $this->tag,
            'attributes' => $this->collectAttributes(),
            'inner' => $this->inner,
        ]);
    }

    public function collectAttributes(): Collection
    {
        return collect($this->attributes)
            ->map(fn ($attribute) => trim($attribute))
            ->sortKeysUsing(fn ($key) => -array_search($key, ['rel', 'hreflang', 'title', 'name', 'href', 'property', 'description', 'content']))
            ->pipeThrough($this->attributesPipeline);
    }
}
