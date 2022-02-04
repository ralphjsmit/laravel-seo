<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use RalphJSmit\Laravel\SEO\Models\SEO;

trait HasSEO
{
    public function addSEO(): static
    {
        $this->seo()->create();

        return $this;
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(SEO::class, 'model');
    }
}