<?php

namespace RalphJSmit\Laravel\SEO\Support;

class LinkTag extends Tag
{
    public string $tag = 'link';

    public function __construct(
        string $rel,
        string $href,
    ) {
        $this->attributes['rel'] = $rel;
        $this->attributes['href'] = $href;
    }
}
