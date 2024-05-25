<?php

namespace RalphJSmit\Laravel\SEO\Schema;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use RalphJSmit\Helpers\Laravel\Pipe\Pipeable;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\Support\Tag;

class CustomSchema extends Tag
{
    use Pipeable;

    public string $tag = 'script';

    public array $attributes = [
        'type' => 'application/ld+json',
    ];

    function __construct(iterable|Arrayable $inner)
    {
        $this->inner = new HtmlString(
            collect($inner)->toJson()
        );
    }
}
