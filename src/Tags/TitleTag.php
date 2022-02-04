<?php

namespace RalphJSmit\Laravel\SEO\Tags;

use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\Support\Tag;

class TitleTag extends Tag
{
    public string $tag = 'title';

    public function __construct(
        public string $inner,
    ) {}

    public static function initialize(?SEOData $SEOData): Tag|null
    {
        $title = $SEOData?->title;

        if ( ! $title ) {
            return null;
        }

        return new static(
            inner: trim($title),
        );
    }
}