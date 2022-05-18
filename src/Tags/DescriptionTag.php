<?php

namespace RalphJSmit\Laravel\SEO\Tags;

use RalphJSmit\Laravel\SEO\Support\MetaTag;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class DescriptionTag extends MetaTag
{
    public static function initialize(?SEOData $SEOData): MetaTag|null
    {
        $description = $SEOData?->description;

        if ( ! $description ) {
            return null;
        }

        return new MetaTag(
            name: 'description',
            content: trim($description)
        );
    }
}