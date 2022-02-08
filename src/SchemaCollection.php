<?php

namespace RalphJSmit\Laravel\SEO;

use Closure;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Schema\ArticleSchema;
use RalphJSmit\Laravel\SEO\Schema\BreadcrumbList;
use RalphJSmit\Laravel\SEO\Schema\BreadcrumbListSchema;
use RalphJSmit\Laravel\SEO\Schema\Schema;

class SchemaCollection extends Collection
{
    protected array $dictionary = [
        'article' => ArticleSchema::class,
        'breadcrumbs' => BreadcrumbListSchema::class,
    ];

    public array $markup = [];

    public function addArticle(Closure $builder = null): static
    {
        $this->markup[$this->dictionary['article']][] = $builder ?: fn (Schema $schema): Schema => $schema;

        return $this;
    }

    public function addBreadcrumbs(Closure $builder = null): static
    {
        $this->markup[$this->dictionary['breadcrumbs']][] = $builder ?: fn (Schema $schema): Schema => $schema;

        return $this;
    }

    public static function initialize(): static
    {
        return new static();
    }
}                                  