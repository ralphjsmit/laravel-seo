<?php

namespace RalphJSmit\Laravel\SEO\Tags;

use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Support\LinkTag;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class FaviconTag extends LinkTag
{
    public static function initialize(?SEOData $SEOData): static|null
    {
        $favicon = $SEOData?->favicon;

        if ( ! $favicon ) {
            return null;
        }

        return new static(
            rel: 'shortcut icon',
            href: $favicon,
        );
    }

    public function collectAttributes(): Collection
    {
        return parent::collectAttributes()
            ->sortKeys();
    }
}