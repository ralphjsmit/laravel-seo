<?php

namespace RalphJSmit\Laravel\SEO\Schema;

use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class BreadcrumbListSchema extends CustomSchemaFluent
{
    public Collection $breadcrumbs;

    public string $type = 'BreadcrumbList';

    public function appendBreadcrumbs(array $breadcrumbs): static
    {
        foreach ($breadcrumbs as $page => $url) {
            $this->breadcrumbs->put($page, $url);
        }

        return $this;
    }

    public function initializeMarkup(SEOData $SEOData): void
    {
        $this->breadcrumbs = collect([
            $SEOData->title => $SEOData->url,
        ]);
    }

    public function generateInner(): Collection
    {
        return collect([
            '@context' => 'https://schema.org',
            '@type' => $this->type,
            'itemListElement' => $this->breadcrumbs
                ->reduce(function (Collection $carry, string $url, string $pagename): Collection {
                    return $carry->push([
                        '@type' => 'ListItem',
                        'position' => $carry->count() + 1,
                        'name' => $pagename,
                        'item' => $url,
                    ]);
                }, new Collection),
        ])
            ->pipeThrough($this->markupTransformers);
    }

    public function prependBreadcrumbs(array $breadcrumbs): static
    {
        foreach (array_reverse($breadcrumbs) as $pagename => $url) {
            $this->breadcrumbs->prepend($url, $pagename);
        }

        return $this;
    }
}
