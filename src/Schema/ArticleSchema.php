<?php

namespace RalphJSmit\Laravel\SEO\Schema;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class ArticleSchema extends Schema
{
    public array $authors = [];

    public ?Carbon $datePublished = null;

    public ?Carbon $dateModified = null;

    public ?string $description = null;

    public ?string $headline = null;

    public ?string $image = null;

    public string $type = 'Article';

    public ?string $url = null;

    public ?string $articleBody = null;

    public function addAuthor(string $authorName): static
    {
        if ( empty($this->authors) ) {
            $this->authors = [
                '@type' => 'Person',
                'name' => $authorName,
            ];

            return $this;
        }

        $this->authors = [
            $this->authors,
            [
                '@type' => 'Person',
                'name' => $authorName,
            ],
        ];

        return $this;
    }

    public function initializeMarkup(SEOData $SEOData, array $markupBuilders): void
    {
        $this->url = $SEOData->url;

        $properties = [
            'headline' => 'title',
            'description' => 'description',
            'image' => 'image',
            'datePublished' => 'published_time',
            'dateModified' => 'modified_time',
            'articleBody' => 'articleBody',
        ];

        foreach ($properties as $markupProperty => $SEODataProperty) {
            if ( $SEOData->{$SEODataProperty} ) {
                $this->{$markupProperty} = $SEOData->{$SEODataProperty};
            }
        }

        if ( $SEOData->author ) {
            $this->authors = [
                '@type' => 'Person',
                'name' => $SEOData->author,
            ];
        }
    }

    public function generateInner(): string
    {
        return collect([
            '@context' => 'https://schema.org',
            '@type' => $this->type,
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $this->url,
            ],
            'datePublished' => $this->datePublished->toIso8601String(),
            'dateUpdated' => $this->dateModified->toIso8601String(),
            'headline' => $this->headline,
        ])
            ->when($this->authors, fn (Collection $collection): Collection => $collection->put('author', $this->authors))
            ->when($this->description, fn (Collection $collection): Collection => $collection->put('description', $this->description))
            ->when($this->image, fn (Collection $collection): Collection => $collection->put('image', $this->image))
            ->when($this->articleBody, fn (Collection $collection): Collection => $collection->put('articleBody', $this->articleBody))
            ->pipeThrough($this->markupTransformers)
            ->toJson();
    }
}