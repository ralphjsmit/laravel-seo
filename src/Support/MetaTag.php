<?php

namespace RalphJSmit\Laravel\SEO\Support;

class MetaTag extends Tag
{
    public string $tag = 'meta';

    public function __construct(
        public string $name,
        public string $content,
    ) {}
}