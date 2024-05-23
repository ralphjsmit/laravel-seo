<?php

namespace RalphJSmit\Laravel\SEO\Schema;

use Illuminate\Support\HtmlString;
use RalphJSmit\Laravel\SEO\Support\SEOData;

/**
 * @see https://developers.google.com/search/docs/appearance/structured-data/faqpage
 */
class FaqPageSchema extends Schema
{
    public string $type = 'FAQPage';

    public array $questions = [];

    public function addQuestion(
        string $name,
        string $acceptedAnswer
    ): static {
        $this->questions[] = [
            '@type' => 'Question',
            'name' => $name,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $acceptedAnswer,
            ],
        ];

        return $this;
    }

    public function initializeMarkup(SEOData $SEOData, array $markupBuilders): void
    {
        //
    }

    public function generateInner(): HtmlString
    {
        $inner = collect([
            '@context' => 'https://schema.org',
            '@type' => $this->type,
            'mainEntity' => $this->questions,
        ])
            ->pipeThrough($this->markupTransformers)
            ->toJson();

        return new HtmlString($inner);
    }
}
