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
            'image' => config('seo.image.fallback'),
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

        return $SEOData->pipethrough(
            SEOManager::getSEODataTransformers()
        );
    }

    public function for(Model $model): static
    {
        $this->model = $model;

        // The tags collection is already initialized when constructing the manager. Here, we'll
        // initialize the collection again, but this time we pass the model to the initializer.
        // The initializes will pass the generated SEOData to all underlying initializers, ensuring that
        // the tags are always fully up-to-date and no remnants from previous initializations are present.
        $this->tags = TagCollection::initialize(
            $this->fillSEOData($this->model->seo?->prepareForUsage() ?? new SEOData())
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
}