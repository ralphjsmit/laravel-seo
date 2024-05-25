<?php

namespace RalphJSmit\Laravel\SEO;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Schema\ArticleSchema;
use RalphJSmit\Laravel\SEO\Schema\BreadcrumbListSchema;
use RalphJSmit\Laravel\SEO\Schema\FaqPageSchema;
use RalphJSmit\Laravel\SEO\Schema\Schema;
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
        'breadcrumbs' => BreadcrumbListSchema::class,
        'faqPage' => FaqPageSchema::class,
    ];

    public array $markup = [];

    /**
     * @deprecated use withArticle instead
     */
    public function addArticle(?Closure $builder = null): static
    {
        $this->markup[$this->dictionary['article']][] = $builder ?: fn (Schema $schema): Schema => $schema;

        return $this;
    }

    public function addBreadcrumbs(?Closure $builder = null): static
    {
        $this->markup[$this->dictionary['breadcrumbs']][] = $builder ?: fn (Schema $schema): Schema => $schema;

        return $this;
    }

    /**
     * @param null|(Closure(SEOData $SEOData, Collection $article): Collection) $builder
     */
    function withArticle(null|array|Closure $builder = null): static
    {
        return $this->add(function (SEOData $SEOData) use ($builder) {
            $schema = collect([
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => $SEOData->url,
                ],
                'datePublished' => $SEOData->published_time->toIso8601String(),
                'dateModified' => $SEOData->modified_time->toIso8601String(),
                'headline' => $SEOData->title,
                'description' => $SEOData->description,
                'image' => $SEOData->image,
            ])->when($SEOData->author, fn (Collection $schema) => $schema->put('author', [[
                '@type' => 'Person',
                'name' => $SEOData->author,
            ]]));

            if ($builder) {
                $schema = $builder($SEOData, $schema);
            }

            return $schema->filter();
        });
    }

    /**
     * @param null|(Closure(SEOData $SEOData, Collection $breadcrumbList): Collection) $builder
     */
    function withBreadcrumbList(null|array|Closure $builder = null): static
    {
        return $this->add(function (SEOData $SEOData) use ($builder) {
            $schema = collect([
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => collect([
                    [
                        '@type' => 'ListItem',
                        'name' => $SEOData->title,
                        'item' => $SEOData->url,
                        'position' => 1,
                    ]
                ]),
            ]);

            if ($builder) {
                $schema = $builder($SEOData, $schema);

                /**
                 * Make sure position are in the right order after builder manipulation
                 */
                $schema->put(
                    'itemListElement',
                    collect($schema->get('itemListElement', []))
                        ->values()
                        ->map(fn (array $item, int $key) => [...$item, 'position' => $key + 1])
                );
            }


            return $schema->filter();
        });
    }

    public static function initialize(): static
    {
        return new static();
    }
}
