<?php

namespace RalphJSmit\Laravel\SEO\Support;

class SitemapTag extends LinkTag
{
    public array $attributes = [
        'type' => 'application/xml',
        'rel' => 'sitemap',
        'title' => 'Sitemap',
    ];

    public function __construct(
        string $href
    ) {
        $this->attributes['href'] = $href;
    }
}
