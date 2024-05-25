<?php

namespace RalphJSmit\Laravel\SEO\Schema;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use RalphJSmit\Helpers\Laravel\Pipe\Pipeable;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\Support\Tag;

/**
 * @deprecated Use CustomSchema paradigm
 */
abstract class Schema extends Tag
{
    use Pipeable;

    public string $tag = 'script';

    public array $attributes = [
        'type' => 'application/ld+json',
    ];

    public Collection $markup;

    public array $markupTransformers = [];

    public function __construct(SEOData $SEOData, array $markupBuilders = [])
    {
        $this->initializeMarkup($SEOData, $markupBuilders);

        $this->pipeThrough($markupBuilders);

        $this->inner = $this->generateInner();
    }

    abstract public function generateInner(): HtmlString;

    abstract public function initializeMarkup(SEOData $SEOData, array $markupBuilders): void;

    public function markup(Closure $transformer): static
    {
        $this->markupTransformers[] = $transformer;

        return $this;
    }
}
