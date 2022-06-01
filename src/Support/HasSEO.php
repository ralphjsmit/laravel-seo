<?php

namespace RalphJSmit\Laravel\SEO\Support;

use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSEO
{
    public function addSEO(): static
    {
        $this->seo()->create();

        return $this;
    }

    protected static function bootHasSEO(): void
    {
        static::created(fn (self $model): self => $model->addSEO());
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(config('seo.model'), 'model')->withDefault();
    }
}