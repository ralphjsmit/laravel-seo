<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Carbon\Carbon;

class SEOData
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $image = null,
        public bool $enableTitleSuffix = true,
        public ?string $site_name = null,
        public ?ImageMeta $imageMeta = null,
        public ?string $type = 'website',
        public ?Carbon $published_time = null,
        public ?Carbon $modified_time = null,
        public ?string $section = null,
        public ?array $tags = null,
    ) {}

    public function imageMeta(): ?ImageMeta
    {
        return $this->imageMeta ??= new ImageMeta($this->image);
    }
}