<?php

namespace RalphJSmit\Laravel\SEO;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Schema\ArticleSchema;
use RalphJSmit\Laravel\SEO\Schema\BreadcrumbListSchema;
use RalphJSmit\Laravel\SEO\Schema\FaqPageSchema;
use RalphJSmit\Laravel\SEO\Support\SEOData;

/**
 * @template TKey of array-key
 *
 * @extends Collection<TKey, iterable|Arrayable|(Closure(SEOData $SEOData):iterable|Arrayable)>
 */
class SchemaCollection extends Collection
{
    protected array $dictionary = [
        'article' => ArticleSchema::class,
        'breadcrumb_list' => BreadcrumbListSchema::class,
        'faq_page' => FaqPageSchema::class,
    ];

    public array $markup = [];

    public function addArticle(?Closure $builder = null): static
    {
        $this->markup[$this->dictionary['article']][] = $builder ?: fn (ArticleSchema $schema): ArticleSchema => $schema;

        return $this;
    }

    public function addBreadcrumbs(?Closure $builder = null): static
    {
        $this->markup[$this->dictionary['breadcrumb_list']][] = $builder ?: fn (BreadcrumbListSchema $schema): BreadcrumbListSchema => $schema;

        return $this;
    }

    public function addFaqPage(?Closure $builder = null): static
    {
        $this->markup[$this->dictionary['faq_page']][] = $builder ?: fn (FaqPageSchema $schema): FaqPageSchema => $schema;

        return $this;
    }

    public static function initialize(): static
    {
        return new static;
    }
}
