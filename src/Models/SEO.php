<?php

namespace RalphJSmit\Laravel\SEO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class SEO extends Model
{
    protected $guarded = [];

    public $table = 'seo';

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function prepareForUsage(): SEOData
    {
        if ( method_exists($this->model, 'getDynamicSEOData') ) {
            $overrides = $this->model->getDynamicSEOData();
        }

        if ( method_exists($this->model, 'enableTitleSuffix') ) {
            $enableTitleSuffix = $this->model->enableTitleSuffix();
        } elseif ( property_exists($this->model, 'enableTitleSuffix') ) {
            $enableTitleSuffix = $this->model->enableTitleSuffix;
        } else {
            $enableTitleSuffix = true;
        }

        return new SEOData(
            description: $overrides->description ?? ( config('seo.fallback_description') ?? $this->description ),
            title      : Str::of($overrides->title ?? $this->title)->when($enableTitleSuffix, fn (Stringable $str) => $str->append(config('seo.title.suffix'))),
        );
    }
}