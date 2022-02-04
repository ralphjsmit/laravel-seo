<?php

namespace RalphJSmit\Laravel\SEO\Support;

class SEOData
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $image = null,
        public bool $enableTitleSuffix = true,
        public ?string $site_name = null,
        public ?ImageMeta $imageMeta = null,
    ) {}

    public function imageMeta(): ?ImageMeta
    {
        return $this->imageMeta ??= new ImageMeta($this->image);
    }
}