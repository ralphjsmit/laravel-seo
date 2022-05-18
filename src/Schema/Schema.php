<?php

namespace RalphJSmit\Laravel\SEO\Schema;

use Closure;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\Support\Tag;

abstract class Schema extends Tag
{
    public array $attributes = [
        'type' => 'application/ld+json',
    ];

    public string $context = 'https://schema.org/';

    public Collection $markup;

    public array $markupTransformers = [];

    public string $tag = 'script';

    public function __construct(
        SEOData $SEOData,
        array $markupBuilders = [],
    ) {
        $this->initializeMarkup($SEOData, $markupBuilders);

        foreach ($markupBuilders as $markupBuilder) {
            $markupBuilder($this);
        }

        $this->inner = $this->generateInner();
    }

    abstract public function generateInner(): string;

    abstract public function initializeMarkup(SEOData $SEOData, array $markupBuilders): void;

    public function markup(Closure $transformer): static
    {
        $this->markupTransformers[] = $transformer;

        return $this;
    }
}