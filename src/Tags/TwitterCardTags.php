<?php

namespace RalphJSmit\Laravel\SEO\Tags;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Support\RenderableCollection;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\Support\TwitterCardTag;
use RalphJSmit\Laravel\SEO\Tags\TwitterCard\Summary;
use RalphJSmit\Laravel\SEO\Tags\TwitterCard\SummaryLargeImage;

class TwitterCardTags extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $SEOData): ?static
    {
        $collection = new static;

        // No generic image that spans multiple pages
        if ($SEOData->image && $SEOData->image !== secure_url(config('seo.image.fallback')) && $SEOData->imageMeta?->height > 0) {
            // Only one Twitter card can be pushed. The `summary_large_image` card
            // is tried first, then it falls back to the normal `summary` card.
            $imageMetaWidthDividedByHeight = $SEOData->imageMeta->width / $SEOData->imageMeta->height;

            if ($imageMetaWidthDividedByHeight < 1.5) {
                // Summary large card has aspect ratio of 2:1. Aspect ratios of < 1 are closer to 1:1
                // then they are to 2:1. Assuming most images are landscape, so fallback to 2:1.
                $collection->push(Summary::initialize($SEOData));
            } else {
                $collection->push(SummaryLargeImage::initialize($SEOData));
            }
        } else {
            if ($SEOData->image && ! $SEOData->imageMeta) {
                // Image external URL...
                $collection->push(SummaryLargeImage::initialize($SEOData));
            } else {
                $collection->push(new TwitterCardTag('card', 'summary'));
            }
        }

        if ($SEOData->openGraphTitle) {
            $collection->push(new TwitterCardTag('title', $SEOData->openGraphTitle));
        } elseif ($SEOData->title) {
            $collection->push(new TwitterCardTag('title', $SEOData->title));
        }

        if ($SEOData->description) {
            $collection->push(new TwitterCardTag('description', $SEOData->description));
        }

        if ($SEOData->twitter_username && $SEOData->twitter_username !== '@') {
            $collection->push(new TwitterCardTag('site', $SEOData->twitter_username));
        }

        return $collection;
    }
}
