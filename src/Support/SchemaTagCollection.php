<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Schema\CustomSchema;

class SchemaTagCollection extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(?SEOData $SEOData = null): ?static
    {
        $schemas = $SEOData?->schema;

        if (! $schemas) {
            return null;
        }

        $collection = new static;

        foreach ($schemas as $schema) {
            $collection->push(new CustomSchema(value($schema, $SEOData)));
        }

        foreach ($schemas->markup as $markupClass => $markupBuilders) {
            $collection->push(new $markupClass($SEOData, $markupBuilders));
        }

        return $collection;
    }
}
