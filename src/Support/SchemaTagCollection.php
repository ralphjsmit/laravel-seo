<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Schema\Schema;

class SchemaTagCollection extends Collection
{
    use RenderableCollection;

    public static function initialize(SEOData $SEOData): ?static
    {
        $collection = new static();

        if ( ! $SEOData->schema ) {
            return null;
        }

        $SEOData->schema->each(function (Schema $item) {
            //
        });

        return $collection;
    }
}