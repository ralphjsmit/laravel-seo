<?php

namespace RalphJSmit\Laravel\SEO\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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

        if ( method_exists($this->model, 'getSEOImageUrl') ) {
            $image = $this->model->getSEOImageUrl();
        }

        return new SEOData(
            title            : $overrides->title ?? $this->title,
            description      : $overrides->description ?? $this->description,
            image            : $image ?? $this->image,
            enableTitleSuffix: $enableTitleSuffix,
        );
    }
}