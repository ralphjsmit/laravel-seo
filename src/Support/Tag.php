<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

abstract class Tag implements Renderable
{
    const ATTRIBUTES_ORDER = ['rel', 'hreflang', 'title', 'name', 'href', 'property', 'description', 'content'];

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
    public null | string | HtmlString $inner = null;

    public array $attributesPipeline = [];

    public function render(): View
    {
        return view('seo::tags.tag', [
            'tag' => $this->tag,
            'attributes' => $this->collectAttributes(),
            'inner' => $this->getInner(),
        ]);
    }

    public function collectAttributes(): Collection
    {
        return collect($this->attributes)
            ->map(fn (string | bool | HtmlString $attribute) => is_string($attribute) ? trim($attribute) : $attribute)
            ->sortKeysUsing(function ($a, $b) {
                $indexA = array_search($a, static::ATTRIBUTES_ORDER);
                $indexB = array_search($b, static::ATTRIBUTES_ORDER);

                return match (true) {
                    $indexB === $indexA => 0, // keep the order defined in $attributes if neither $a or $b are in ATTRIBUTES_ORDER
                    $indexA === false => 1,
                    $indexB === false => -1,
                    default => $indexA - $indexB
                };
            })
            ->pipeThrough($this->attributesPipeline);
    }

    public function getInner(): null | string | HtmlString
    {
        return $this->inner;
    }
}
