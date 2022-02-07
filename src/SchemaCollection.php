<?php

namespace RalphJSmit\Laravel\SEO;

use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Schema\ArticleSchema;
use RalphJSmit\Laravel\SEO\Support\RenderableCollection;

class SchemaCollection extends Collection
{
    use RenderableCollection;

    public function addArticle(): static
    {
        return $this->push(new ArticleSchema());
    }

    public static function initialize(): static
    {
        $collection = new static();

        return $collection;
    }
}