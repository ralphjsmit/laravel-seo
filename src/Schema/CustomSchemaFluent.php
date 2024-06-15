<?php

namespace RalphJSmit\Laravel\SEO\Schema;

use Closure;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Support\SEOData;

abstract class CustomSchemaFluent extends CustomSchema
{
    public array $markupTransformers = [];

    public function __construct(SEOData $SEOData, array $markupBuilders = [])
    {
        $this->initializeMarkup($SEOData);

        // `$markupBuilders` are closures that modify this fluent schema
        // tag object and can call methods on it to change items...
        foreach ($markupBuilders as $markupBuilder) {
            $markupBuilder($this, $SEOData);
        }

        parent::__construct($this->generateInner());
    }

    abstract public function initializeMarkup(SEOData $SEOData): void;

    abstract public function generateInner(): Collection;

    public function markup(Closure $transformer): static
    {
        $this->markupTransformers[] = $transformer;

        return $this;
    }
}
