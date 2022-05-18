<?php

namespace RalphJSmit\Laravel\SEO;

use Closure;

class SEOManager
{
    protected array $tagTransformers = [];

    protected array $SEODataTransformers = [];

    public function SEODataTransformer(Closure $transformer): static
    {
        $this->SEODataTransformers[] = $transformer;

        return $this;
    }

    public function tagTransformer(Closure $transformer): static
    {
        $this->tagTransformers[] = $transformer;

        return $this;
    }

    public function getTagTransformers(): array
    {
        return $this->tagTransformers;
    }

    public function getSEODataTransformers(): array
    {
        return $this->SEODataTransformers;
    }
}