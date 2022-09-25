<?php

namespace RalphJSmit\Laravel\SEO\Tags;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Support\RenderableCollection;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\Support\SitemapTag as SitemapTagSupport;

class SitemapTag extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $SEOData = null): static
    {
        $collection = new static();

        if ( $sitemap = config('seo.sitemap') ) {
            $collection->push(new SitemapTagSupport($sitemap));
        }

        return $collection;
    }
}