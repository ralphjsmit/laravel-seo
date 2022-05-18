<?php

namespace RalphJSmit\Laravel\SEO\Tags;

use RalphJSmit\Laravel\SEO\Support\MetaTag;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class AuthorTag extends MetaTag
{
    public static function initialize(?SEOData $SEOData): MetaTag|null
    {
        $author = $SEOData?->author;

        if ( ! $author ) {
            return null;
        }

        return new MetaTag(
            name: 'author',
            content: trim($author)
        );
    }
}