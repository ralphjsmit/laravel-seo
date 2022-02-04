<?php

namespace RalphJSmit\Laravel\SEO;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\Tags\DescriptionTag;
use RalphJSmit\Laravel\SEO\Tags\RobotsTags;

class TagCollection extends Collection
{
    public static function initialize(SEOData $SEOData = null): static
    {
        $collection = new static();

        $tags = collect([
            RobotsTags::initialize(),
            DescriptionTag::initialize($SEOData),
        ])->reject(fn (?Renderable $item) => $item === null);

        foreach ($tags as $tag) {
            $collection->push($tag);
        }

        return $collection;
    }
}