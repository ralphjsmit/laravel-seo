<?php

use RalphJSmit\Laravel\SEO\Schema\CustomSchema;

it('can construct a custom faq schema', function () {
    $schema = new CustomSchema($this->faqTestSchema);

    expect((string) $schema->render())
        ->toBe(
            '<script type="application/ld+json">' . json_encode($this->faqTestSchema) . '</script>'
        );
});
