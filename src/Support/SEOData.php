<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Carbon\CarbonInterface;
use Illuminate\Support\Str;
use RalphJSmit\Helpers\Laravel\Pipe\Pipeable;
use RalphJSmit\Laravel\SEO\SchemaCollection;

class SEOData
{
    use Pipeable;

    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $author = null,
        public ?string $image = null,
        public ?string $url = null,
        public bool $enableTitleSuffix = true,
        public ?ImageMeta $imageMeta = null,
        public ?CarbonInterface $published_time = null,
        public ?CarbonInterface $modified_time = null,
        public ?string $articleBody = null,
        public ?string $section = null,
        public ?array $tags = null,
        public ?string $twitter_username = null,
        public ?SchemaCollection $schema = null,
        public ?string $type = 'website',
        public ?string $site_name = null,
        public ?string $favicon = null,
        public ?string $locale = null,
        public ?string $robots = null,
        public ?string $canonical_url = null,
    ) {
        if ( $this->locale === null ) {
            $this->locale = Str::of(app()->getLocale())->lower()->kebab();
        }
    }

    public function imageMeta(): ?ImageMeta
    {
        if ( $this->image ) {
            return $this->imageMeta ??= new ImageMeta($this->image);
        }

        return null;
    }

    public function markAsNoindex(): static
    {
        $this->robots = 'noindex, nofollow';

        return $this;
    }
}