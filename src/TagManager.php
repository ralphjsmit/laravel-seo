<?php

namespace RalphJSmit\Laravel\SEO;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Facades\SEOManager;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class TagManager implements Renderable
{
    public Model $model;
    public TagCollection $tags;

    public function __construct()
    {
        $this->tags = TagCollection::initialize(
            $this->fillSEOData()
        );
    }

    public function fillSEOData(SEOData $SEOData = null): SEOData
    {
        $SEOData ??= new SEOData();

        $defaults = [
            'title' => config('seo.title.infer_title_from_url') ? $this->inferTitleFromUrl() : null,
            'description' => config('seo.description.fallback'),
            'image' => config('seo.image.fallback') ? config('seo.image.fallback') : null,
            'site_name' => config('seo.site_name'),
            'author' => config('seo.author.fallback'),
            'twitter_username' => Str::of(config('seo.twitter.@username'))->start('@'),
            'favicon' => config('seo.favicon'),
        ];

        foreach ($defaults as $property => $defaultValue) {
            if ( $SEOData->{$property} === null ) {
                $SEOData->{$property} = $defaultValue;
            }
        }

        if ( $SEOData->enableTitleSuffix ) {
            $SEOData->title .= config('seo.title.suffix');
        }

        if ( $SEOData->image && ! filter_var($SEOData->image, FILTER_VALIDATE_URL) ) {
            $SEOData->imageMeta();

            $SEOData->image = secure_url($SEOData->image);
        }

        if ( $SEOData->favicon && ! filter_var($SEOData->favicon, FILTER_VALIDATE_URL) ) {
            $SEOData->favicon = secure_url($SEOData->favicon);
        }

        if ( ! $SEOData->url ) {
            $SEOData->url = url()->current();
        }

        if ( $SEOData->url === url('/') && ( $homepageTitle = config('seo.title.homepage_title') ) ) {
            $SEOData->title = $homepageTitle;
        }

        foreach (SEOManager::getSEODataTransformers() as $SEODataTransformer) {
            $SEODataTransformer($SEOData);
        }

        return $SEOData;
    }

    public function for(Model $model): static
    {
        $this->model = $model;

        // The tags collection is already initialized when constructing the manager. Here, we'll
        // initialize the collection again, but this time we pass the model to the initializer.
        // The initializes will pass the generated SEOData to all underlying initializers, ensuring that
        // the tags are always fully up-to-date and no remnants from previous initializations are present.
        $this->tags = TagCollection::initialize(
            $this->fillSEOData($this->prepareForUsage($this->model))
        );

        return $this;
    }

    protected function inferTitleFromUrl(): string
    {
        return Str::of(url()->current())
            ->afterLast('/')
            ->headline();
    }

    public function render(): string
    {
        return $this->tags
            ->pipeThrough(SEOManager::getTagTransformers())
            ->reduce(function (string $carry, Renderable $item) {
                return $carry .= Str::of($item->render())->trim()->trim(PHP_EOL);
            }, '');
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function prepareForUsage(Model $model): SEOData
    {
        if ( method_exists($model, 'getDynamicSEOData') ) {
            $overrides = $this->model->getDynamicSEOData();
        }

        if ( method_exists($model, 'enableTitleSuffix') ) {
            $enableTitleSuffix = $model->enableTitleSuffix();
        } elseif ( property_exists($model, 'enableTitleSuffix') ) {
            $enableTitleSuffix = $model->enableTitleSuffix;
        }

        return new SEOData(
            title            : $overrides->title ?? $model->seo?->title,
            description      : $overrides->description ?? $model->seo?->description,
            author           : $overrides->author ?? $model->seo?->author,
            image            : $image ?? ( $overrides->image ?? $model->seo?->image ),
            url              : $overrides->url ?? null,
            enableTitleSuffix: $enableTitleSuffix ?? true,
            published_time   : $overrides->published_time ?? ( $model->seo?->model?->created_at ?? null ),
            modified_time    : $overrides->modified_time ?? ( $model->seo?->model?->updated_at ?? null ),
            articleBody      : $overrides->articleBody ?? null,
            section          : $overrides->section ?? null,
            tags             : $overrides->tags ?? null,
            schema           : $overrides->schema ?? null,
            type             : $overrides->type ?? null,
            locale           : $overrides->locale ?? null,
        );
    }
}