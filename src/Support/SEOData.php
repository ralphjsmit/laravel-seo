<?php

namespace RalphJSmit\Laravel\SEO\Support;

class SEOData
{
    public function __construct(
        public ?string $description = null,
        public ?string $title = null,
    ) {}
}