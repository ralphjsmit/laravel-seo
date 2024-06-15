<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class OpenGraphTag extends Tag
{
    public string $tag = 'meta';

    public function __construct(
        string $property,
        string | HtmlString $content,
    ) {
        $this->attributes['property'] = $property;
        $this->attributes['content'] = $content;

        $this->attributesPipeline[] = function (Collection $collection) {
            return $collection->mapWithKeys(function (mixed $value, string $key) {
                if ($key === 'property') {
                    $value = 'og:' . $value;
                }

                return [$key => $value];
            });
        };
    }
}
