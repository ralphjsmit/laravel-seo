<?php

namespace RalphJSmit\Laravel\SEO\Tags;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Support\LinkTag;
use RalphJSmit\Laravel\SEO\Support\RenderableCollection;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class CanonicalTag extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $SEOData = null): static
    {
        $collection = new static();

        if ( config('seo.canonical_link') ) {
            $collection->push(new LinkTag('canonical', $SEOData->canonical_url ?? $SEOData->url));
        }

        return $collection;
    }
}