<?php

namespace RalphJSmit\Laravel\SEO\Support;

class LinkTag extends Tag
{
    public string $tag = 'link';

    public function __construct(
        public string $rel,
        public string $href,
    ) {}
}