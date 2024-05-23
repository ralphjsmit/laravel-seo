<?php

namespace RalphJSmit\Laravel\SEO\Support;

class AlternateTag extends LinkTag
{
    public function __construct(
        public string $hreflang,
        string $href,
    ) {
        parent::__construct('alternate', $href);
    }
}
