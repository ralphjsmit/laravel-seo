<?php

namespace RalphJSmit\Laravel\SEO;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Support\SchemaTagCollection;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\Tags\AuthorTag;
use RalphJSmit\Laravel\SEO\Tags\DescriptionTag;
use RalphJSmit\Laravel\SEO\Tags\ImageTag;
use RalphJSmit\Laravel\SEO\Tags\OpenGraphTags;
use RalphJSmit\Laravel\SEO\Tags\RobotsTags;
use RalphJSmit\Laravel\SEO\Tags\TitleTag;
use RalphJSmit\Laravel\SEO\Tags\TwitterCardTags;

class TagCollection extends Collection
{
    public static function initialize(SEOData $SEOData = null): static
    {
        $collection = new static();

        $tags = collect([
            RobotsTags::initialize(),
            DescriptionTag::initialize($SEOData),
            AuthorTag::initialize($SEOData),
            TitleTag::initialize($SEOData),
            ImageTag::initialize($SEOData),
            OpenGraphTags::initialize($SEOData),
            TwitterCardTags::initialize($SEOData),
            SchemaTagCollection::initialize($SEOData, $SEOData->schema),
        ])->reject(fn (?Renderable $item): bool => $item === null);

        foreach ($tags as $tag) {
            $collection->push($tag);
        }

        return $collection;
    }
}