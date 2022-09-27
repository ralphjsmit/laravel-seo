<?php

namespace RalphJSmit\Laravel\SEO\Tags;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Support\MetaTag;
use RalphJSmit\Laravel\SEO\Support\RenderableCollection;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class RobotsTag extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $SEOData = null): static
    {
        $collection = new static();

        $robotsContent = config('seo.robots.default');

        if ( ! config('seo.robots.force_default') ) {
            $robotsContent = $SEOData?->robots ?? $robotsContent;
        }

        $collection->push(new MetaTag('robots', $robotsContent));

        return $collection;
    }
}