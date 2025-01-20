<?php

namespace RalphJSmit\Laravel\SEO\Schema;

use Illuminate\Support\Collection;
use RalphJSmit\Laravel\SEO\Support\SEOData;

/**
 * @see https://developers.google.com/search/docs/appearance/structured-data/faqpage
 */
class FaqPageSchema extends CustomSchemaFluent
{
    public Collection $questions;

    public string $type = 'FAQPage';

    public function addQuestion(string $name, string $acceptedAnswer): static
    {
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

    public function initializeMarkup(SEOData $SEOData): void
    {
        $this->questions = new Collection;
    }

    public function generateInner(): Collection
    {
        return collect([
            '@context' => 'https://schema.org',
            '@type' => $this->type,
            'mainEntity' => $this->questions,
        ])
            ->pipeThrough($this->markupTransformers);
    }
}
