<?php

namespace RalphJSmit\Laravel\SEO\Tags;

use RalphJSmit\Laravel\SEO\Support\MetaTag;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class ImageTag extends MetaTag
{
    public static function initialize(?SEOData $SEOData): MetaTag|null
    {
        $image = $SEOData?->image;

        if ( ! $image ) {
            return null;
        }

        return new MetaTag(
            name: 'image',
            content: trim($image)
        );
    }
}