<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class TwitterCardTag extends Tag
{
    public string $tag = 'meta';

    public function __construct(
        string $name,
        string | HtmlString $content,
    ) {
        $this->attributes['name'] = $name;
        $this->attributes['content'] = $content;

        $this->attributesPipeline[] = function (Collection $collection) {
            return $collection->mapWithKeys(function (mixed $value, string $key) {
                if ($key === 'name') {
                    $value = 'twitter:' . $value;
                }

                return [$key => $value];
            });
        };
    }
}
