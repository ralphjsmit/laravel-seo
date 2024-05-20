<?php

namespace RalphJSmit\Laravel\SEO\Support;

class AlternateTag extends LinkTag
{
    public string $rel = "alternate";

    public function __construct(
        public string $hreflang,
        public string $href,
    ) {
    }
}
