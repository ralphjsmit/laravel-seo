<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Illuminate\Support\HtmlString;

class MetaTag extends Tag
{
    public string $tag = 'meta';

    public function __construct(
        string $name,
        string | HtmlString $content,
    ) {
        $this->attributes['name'] = $name;
        $this->attributes['content'] = $content;
    }
}
