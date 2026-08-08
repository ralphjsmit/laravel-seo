<?php

namespace RalphJSmit\Laravel\SEO\Schema;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\HtmlString;
use RalphJSmit\Helpers\Laravel\Pipe\Pipeable;
use RalphJSmit\Laravel\SEO\Support\Tag;

class CustomSchema extends Tag
{
    use Pipeable;

    public string $tag = 'script';

    public array $attributes = [
        'type' => 'application/ld+json',
    ];

    public function __construct(iterable | Arrayable $inner)
    {
        $this->inner = new HtmlString(
            collect($inner)->toJson(JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)
        );
    }
}
