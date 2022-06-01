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
        }

        return new SEOData(
            title: $overrides->title ?? $this->title,
            description: $overrides->description ?? $this->description,
            author: $overrides->author ?? $this->author,
            image: $overrides->image ?? $this->image,
            url: $overrides->url ?? null,
            enableTitleSuffix: $enableTitleSuffix ?? true,
            published_time: $overrides->published_time ?? ( $this->model?->created_at ?? null ),
            modified_time: $overrides->modified_time ?? ( $this->model?->updated_at ?? null ),
            articleBody: $overrides->articleBody ?? null,
            section: $overrides->section ?? null,
            tags: $overrides->tags ?? null,
            schema: $overrides->schema ?? null,
            type: $overrides->type ?? null,
            locale: $overrides->locale ?? null,
        );
    }
}