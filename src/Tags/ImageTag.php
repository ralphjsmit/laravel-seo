<?php

namespace RalphJSmit\Laravel\SEO\Tags;

use Illuminate\Support\HtmlString;
use RalphJSmit\Laravel\SEO\Support\MetaTag;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class ImageTag extends MetaTag
{
    public static function initialize(?SEOData $SEOData): ?MetaTag
    {
        $image = $SEOData?->image;

        if (! $image) {
            return null;
        }

        return new MetaTag(
            name: 'image',
            content: new HtmlString($image),
        );
    }
}
