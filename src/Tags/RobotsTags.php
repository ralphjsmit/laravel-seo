<?php

namespace RalphJSmit\Laravel\SEO\Tags;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Support\MetaTag;
use RalphJSmit\Laravel\SEO\Support\RenderableCollection;

class RobotsTags extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(): static
    {
        $collection = new static();

        $collection->push(new MetaTag('robots', 'max-snippet:-1,max-image-preview:large,max-video-preview:-1'));

        return $collection;
    }
}