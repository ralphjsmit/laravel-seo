<?php

namespace RalphJSmit\Laravel\SEO\Support;

class MetaContentTag extends Tag
{
    public string $tag = 'meta';

    public function __construct(
        string $property,
        string $content,
    ) {
        $this->attributes['property'] = $property;
        $this->attributes['content'] = $content;
    }
}
