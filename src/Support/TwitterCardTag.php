<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Illuminate\Support\Collection;

class TwitterCardTag extends Tag
{
    public string $tag = 'meta';

    public function __construct(
        public string $name,
        public string $content,
    ) {
        $this->attributesPipeline[] = function (Collection $collection) {
            return $collection->mapWithKeys(function ($value, $key) {
                if ( $key === 'name' ) {
                    $value = 'twitter:' . $value;
                }

                return [$key => $value];
            });
        };
    }
}