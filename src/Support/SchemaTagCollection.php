<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\SchemaCollection;

class SchemaTagCollection extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $SEOData, SchemaCollection $schema = null): ?static
    {
        $collection = new static();

        if ( ! $schema ) {
            return null;
        }

        foreach ($schema->markup as $markupClass => $markupBuilders) {
            $collection = $collection->push(new $markupClass($SEOData, $markupBuilders));
        }

        return $collection;
    }
}