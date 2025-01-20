![laravel-seo](https://github.com/ralphjsmit/laravel-seo/blob/main/docs/images/seo.jpg)

# Never worry about SEO in Laravel again!

Currently there aren't that many SEO-packages for Laravel and the available ones are quite complex to set up and very decoupled from the database. They only provided you with helpers to generate the tags, but you still had to use those helpers: nothing was generated automatically and they almost do not work out of the box.

This package generates **valid and useful meta tags straight out-of-the-box**, with limited initial configuration, while still providing a simple, but powerful API to work with. It can generate:

1. Title tag (with sitewide suffix)
2. Meta tags (author, description, image, robots, etc.)
3. OpenGraph Tags (Facebook, LinkedIn, etc.)
4. Twitter Tags
5. Structured data (Article, Breadcrumbs, FAQPage, or any custom schema)
6. Favicon
7. Robots tag
8. Alternates links tag

If you're familiar with Spatie's media-library package, this package works in almost the same way, but then only for SEO. I'm sure it will be very helpful for you, as it's usually best for a website to have attention for SEO right from the beginning.

Here are a few examples of what you can do:

```php
$post = Post::find(1);

$post->seo->update([
   'title' => 'My great post',
   'description' => 'This great post will enhance your live.',
]);
```

It will render the SEO tags directly on your page:

```blade
<!DOCTYPE html>
<html>
<head>
    {!! seo()->for($post) !!}

    {{-- No need to separately render a <title> tag or any other meta tags! --}}
</head>
```

It even allows you to **dynamically retrieve SEO data from your model**, without having to save it manually to the SEO model. The below code will require zero additional work from you or from your users:

```php
class Post extends Model
{
    use HasSEO;

    public function getDynamicSEOData(): SEOData
    {
        $pathToFeaturedImageRelativeToPublicPath = // ..;

        // Override only the properties you want:
        return new SEOData(
            title: $this->title,
            description: $this->excerpt,
            image: $pathToFeaturedImageRelativeToPublicPath,
        );
    }
}
```

## Installation

Run the following command to install the package:

```shell
composer require ralphjsmit/laravel-seo
```

Publish the migration and configuration file:

```sh
php artisan vendor:publish --tag="seo-migrations"
php artisan vendor:publish --tag="seo-config"
```

Next, go to the newly published config file in `config/seo.php` and make sure that all the settings are correct. Those settings are all sort of default values:

```php
<?php

return [
    /**
     * Use this setting to specify the site name that will be used in OpenGraph tags.
     */
    'site_name' => null,

    /**
     * Use this setting to specify the path to the sitemap of your website. This exact path will outputted, so
     * you can use both a hardcoded url and a relative path. We recommend the latter.
     *
     * Example: '/storage/sitemap.xml'
     * Do not forget the slash at the start. This will tell the search engine that the path is relative
     * to the root domain and not relative to the current URL. The `spatie/laravel-sitemap` package
     * is a great package to generate sitemaps for your application.
     */
    'sitemap' => null,

    /**
     * Use this setting to specify whether you want self-referencing `<link rel="canonical" href="$url">` tags to
     * be added to the head of every page. There has been some debate whether this is a good practice, but experts
     * from Google and Yoast say that this is the best strategy.
     * See https://yoast.com/rel-canonical/.
     */
    'canonical_link' => true,

    'robots' => [
        /**
         * Use this setting to specify the default value of the robots meta tag. `<meta name="robots" content="noindex">`
         * Overwrite it with the robots attribute of the SEOData object. `SEOData->robots = 'noindex, nofollow'`
         * "max-snippet:-1" Use n chars (-1: Search engine chooses) as a search result snippet.
         * "max-image-preview:large" Max size of a preview in search results.
         * "max-video-preview:-1" Use max seconds (-1: There is no limit) as a video snippet in search results.
         * See https://developers.google.com/search/docs/advanced/robots/robots_meta_tag
         * Default: 'max-snippet:-1, max-image-preview:large, max-video-preview:-1'
         */
        'default' => 'max-snippet:-1,max-image-preview:large,max-video-preview:-1',

        /**
         * Force set the robots `default` value and make it impossible to overwrite it. (e.g. via SEOData->robots)
         * Use case: You need to set `noindex, nofollow` for the entire website without exception.
         * Default: false
         */
        'force_default' => false,
    ],

    /**
     * Use this setting to specify the path to the favicon for your website. The url to it will be generated using the `secure_url()` function,
     * so make sure to make the favicon accessible from the `public` folder.
     *
     * You can use the following filetypes: ico, png, gif, jpeg, svg.
     */
    'favicon' => null,

    'title' => [
        /**
         * Use this setting to let the package automatically infer a title from the url, if no other title
         * was given. This will be very useful on pages where you don't have an Eloquent model for, or where you
         * don't want to hardcode the title.
         *
         * For example, if you have an url with the path '/foo/about-me', we'll automatically set the title to 'About me' and append the site suffix.
         */
        'infer_title_from_url' => true,

        /**
         * Use this setting to provide a suffix that will be added after the title on each page.
         * If you don't want a suffix, you should specify an empty string.
         */
        'suffix' => '',

        /**
         * Use this setting to provide a custom title for the homepage. We will not use the suffix on the homepage,
         * so you'll need to add the suffix manually if you want that. If set to null, we'll determine the title
         * just like the other pages.
         */
        'homepage_title' => null,
    ],

    'description' => [
        /**
         * Use this setting to specify a fallback description, which will be used on places
         * where we don't have a description set via an associated ->seo model or via
         * the ->getDynamicSEOData() method.
         */
        'fallback' => null,
    ],

    'image' => [
        /**
         * Use this setting to specify a fallback image, which will be used on places where you
         * don't have an image set via an associated ->seo model or via the ->getDynamicSEOData() method.
         * This should be a path to an image. The url to the path is generated using the `secure_url()` function (`secure_url($yourProvidedPath)`).
         */
        'fallback' => null,
    ],

    'author' => [
        /**
         * Use this setting to specify a fallback author, which will be used on places where you
         * don't have an author set via an associated ->seo model or via the ->getDynamicSEOData() method.
         */
        'fallback' => null,
    ],

    'twitter' => [
        /**
         * Use this setting to enter your username and include that with the Twitter Card tags.
         * Enter the username like 'yourUserName', so without the '@'.
         */
        '@username' => null,
    ],
];
```

Now, add the following **Blade-code on every page** where you want your SEO-tags to appear:

```blade
{!! seo() !!}
```

This will render a **lot of sensible tags by default**, already **greatly improving your SEO**. It will also render things like the `<title>` tag, so you don't have to render that manually. Additionally, it takes care of things automatically adding the `inertia` attribute to your `<title>` tag, allowing it to dynamically update whenever the user navigates to a different route on the frontend.

To really profit from this package, you can **associate an Eloquent model with a SEO-model**. This will allow you to **dynamically fetch SEO data from your model** and this package will generate as much tags as possible for you, based on that data.

To associate an Eloquent model with a SEO-model, add the `HasSEO` trait to your model:

```php
use RalphJSmit\Laravel\SEO\Support\HasSEO;

class Post extends Model
{
    use HasSEO;

    // ...
```

This will automatically create and associate a SEO-model for you when a `Post` is created. You can also manually create a SEO-model for a Post, use the `->addSEO()` method for that (`$post->addSEO()`).

You'll be able to retrieve the SEO-model via the Eloquent `seo` relationship:

```php
$post = Post::find(1);

$seo = $post->seo;
```

On the SEO model, you may **update the following properties**:

1. `title`: this will be used for the `<title>` tag and all the related tags (OpenGraph, Twitter, etc.)
2. `description`: this will be used for the `<meta>` description tag and all the related tags (OpenGraph, Twitter, etc.)
3. `author`: this should be the name of the author and it will be used for the `<meta>` author tag and all the related tags (OpenGraph, Twitter, etc.)
4. `image`: this should be the path to the image you want to use for the `<meta>` image tag and all the related tags (OpenGraph, Twitter, etc.). The url to the image is generated via the `secure_url()` function, so be sure to check that the image is publicly available and that you provide the right path.
5. `robots`
    - Overwrites the default robots value, which is set in the config. (See `'seo.robots.default'`).
    - String like `noindex,nofollow` [(Specifications)](https://developers.google.com/search/docs/advanced/robots/robots_meta_tag), which is added to `<meta name="robots">`

```php
$post = Post::find(1);

$post->seo->update([
   'title' => 'My title for the SEO tag',
   'image' => 'images/posts/1.jpg', // Will point to `public_path('images/posts/1.jpg')`
]);
```

However, it can be a **bit cumbersome to manually update** the SEO-model every time you make a change. That's why I provided the `getDynamicSEOData()` method, which you can use to dynamically fetch the correct data from your own model and pass it to the SEO model:

```php
public function getDynamicSEOData(): SEOData
{
    return new SEOData(
        title: $this->title,
        description: $this->excerpt,
        author: $this->author->fullName,
        alternates: [
            new AlternateTag(
                hreflang: 'en',
                href: "https://example.com/en",
            ),
            new AlternateTag(
                hreflang: 'fr',
                href: "https://example.com/fr",
            ),
        ],
    );
}
```

You are allowed to only override the properties you want and omit the other properties (or pass `null` to them). You can use the following properties:

1. `title`
2. `description`
3. `author` (should be the author's name)
4. `image` (should be the image path and be compatible with `$url = public_path($path)`)
5. `url` (by default it will be `url()->current()`)
6. `enableTitleSuffix` (should be `true` or `false`, this allows you to set a suffix in the `config/seo.php` file, which will be appended to every title)
7. `site_name`
8. `published_time` (should be a `Carbon` instance with the published time. By default, this will be the `created_at` property of your model)
9. `modified_time` (should be a `Carbon` instance with the published time. By default, this will be the `updated_at` property of your model)
10. `section` (should be the name of the section of your content. It is used for OpenGraph article tags and it could be something like the category of the post)
11. `tags` (should be an array with tags. It is used for the OpenGraph article tags)
12. `schema` (this should be a SchemaCollection instance, where you can configure the JSON-LD structured data schema tags)
13. `locale` (this should be the locale of the page. By default, this is derived from `app()->getLocale()` and it looks like `en` or `nl`.)
14. `robots` (should be a string with the content value of the robots meta tag, like `nofollow,noindex`). You can also use the `$SEOData->markAsNoIndex()` to prevent a page from being indexed.
15. `alternates` (should be an array of `AlternateTag`). Will render `<link rel="alternate" ... />` tags.

Finally, you should update your Blade file, so that it can receive your model when generating the tags:

```blade
{!! seo()->for($page) !!}
{{-- Or pass it directly to the `seo()` method: --}}
{!! seo($page ?? null) !!}
```

The following order is used when generating the tags (higher overwrites the lower):

1. Any overwrites from the `SEOManager::SEODataTransformer($closure)` (see below)
2. Data from the `getDynamicSEOData()` method
3. Data from the associated SEO model (`$post->seo`)
4. Default data from the `config/seo.php` file

### Passing SEOData directly from the controller

Another option is to pass a SEOData-object directly from the controller to the layout file, into the `seo()` function.

```php
use Illuminate\Contracts\View\View;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Homepage extends Controller
{
    public function index(): View
    {
        return view('project.frontend.page.homepage.index', [
            'SEOData' => new SEOData(
                title: 'Awesome News - My Project',
                description: 'Lorem Ipsum',
            ),
        ]);
    }
}
```

```blade
{!! seo($SEOData) !!}
```

## Generating JSON-LD structured data

This package can also **generate any structured data** for you (also called schema markup).
Structured data is a very vast subject, so we highly recommend you to check the [Google documentation dedicated to it](https://developers.google.com/search/docs/appearance/structured-data/search-gallery).

Structured data can be added in two ways:
-   Construct custom arrays of the structured data format, which is then rendered by the package in JSON on the correct place.
-   Use one of the 3 pre-defined templates to fluently build your structured data (`Article`, `BreadcrumbList`, `FaqPage`).

### Adding your first schema

Let's add the FAQPage schema markup to our website as an example:

```php
use RalphJSmit\Laravel\SEO\SchemaCollection;

public function getDynamicSEOData(): SEOData
{
    return new SEOData(
        // ...
        schema: SchemaCollection::make()
            ->add(fn (SEOData $SEOData) => [
                // You could use the `$SEOData` to dynamically
                // fetch any data about the current page.
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => [
                    '@type' => 'Question',
                    'name' => 'Your question goes here',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Your answer goes here',
                    ],
                ],
            ]),
    );
}
```

> [!TIP]
> When adding a new schema, you can check the [documentation here](https://developers.google.com/search/docs/appearance/structured-data/faqpage) to know what keys to add.

### Pre-configured Schema: Article and BreadcrumbList

To help you get started with structured data, we added 3 preconfigured schema that you can construct using fluent methods. The following types are available:

1. `Article`
2. `BreadcrumbList`
3. `FAQPage`

### Article schema markup

In order to automatically and fluently generate `Article` schema markup, use the `->addArticle()` method:

```php

use RalphJSmit\Laravel\SEO\SchemaCollection;

public function getDynamicSEOData(): SEOData
{
    return new SEOData(
        // ...
        schema: SchemaCollection::make()->addArticle(),
    );
}
```

This will construct an article schema using all data provided by the `SEOData` object. You can pass a closure to `->addArticle()` method to customize the individual schema markup. This closure will receive an instance of ArticleSchema as its argument. You can an additional author by using the `->addAuthor()` method.

```php
use RalphJSmit\Laravel\SEO\Schema\ArticleSchema;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Collection;

public function getDynamicSEOData(): SEOData
{
    return new SEOData(
        // ...
        title: "A boring title"
        schema: SchemaCollection::make()
            ->addArticle(function (ArticleSchema $article, SEOData $SEOData): ArticleSchema {
                return $article->addAuthor($this->moderator);
            }),
    );
}
```

You can completely customize the schema markup by using the `->markup()` method on the `ArticleSchema` instance:

```php
SchemaCollection::initialize()->addArticle(function (ArticleSchema $article, SEOData $SEOData): ArticleSchema {
    return $article->markup(function (Collection $markup) use ($SEOData): Collection {
        return $markup->put('alternativeHeadline', "Not {$SEOData->title}"); // Set/overwrite alternative headline property to `Will be "Not A boring title"` :)
    });
});
```

> [!TIP]
> Check the Google documentation about [Article](https://developers.google.com/search/docs/appearance/structured-data/article) for more information.

### BreadcrumbList schema markup

You can also add `BreadcrumbList` schema markup by using the `->addBreadcrumbList()` function on the `SchemaCollection`.

By default, the schema will only contain the current url from `$SEOData->url`.

```php
SchemaCollection::initialize()
   ->addBreadcrumbs(function (BreadcrumbListSchema $breadcrumbs, SEOData $SEOData): BreadcrumbListSchema {
        return $breadcrumbs
            ->prependBreadcrumbs([
               'Homepage' => 'https://example.com',
               'Category' => 'https://example.com/test',
            ])
            ->appendBreadcrumbs([
                'Subarticle' => 'https://example.com/test/article/2',
            ])
            ->markup(function (Collection $markup): Collection {
               // ...
            });
    });
```

This code will generate `BreadcrumbList` JSON-LD structured data with the following four pages:

1. Homepage
2. Category
3. [Current page]
4. Subarticle

> [!TIP]
> Check the Google documentation about [BreadcrumbList](https://developers.google.com/search/docs/appearance/structured-data/breadcrumb) for more information.

### FAQPage schema markup

You can also add FAQPage schema markup by using the ->addFaqPage() function on the SchemaCollection:

```php
SchemaCollection::initialize()
    ->addFaqPage(function (FaqPageSchema $faqPage, SEOData $SEOData): FaqPageSchema {
        return $faqPage
           ->addQuestion(name: "Can this package add FaqPage to the schema?", acceptedAnswer: "Yes!")
           ->addQuestion(name: "Does it support multiple questions?", acceptedAnswer: "Of course.");
   });
```

> [!TIP]
> Check the Google documentation about [Faq Page](https://developers.google.com/search/docs/appearance/structured-data/faqpage) for more information.

> [!TIP]
> After generating the structured data, it is always a good idea to [test your website with Google's rich result validator](https://search.google.com/test/rich-results).

## Advanced usage

Sometimes you may have advanced needs that require you to apply your own logic to the `SEOData` class, just before it is used to generate the tags.

To accomplish this, you can use the `SEODataTransformer()` function on the `SEOManager` facade to register one or multiple closures that will be able to modify the `SEOData` instance at the last moment:

```php
// In the `boot()` method of a service provider somewhere
use RalphJSmit\Laravel\SEO\Facades\SEOManager;

SEOManager::SEODataTransformer(function (SEOData $SEOData): SEOData {
    // This will change the title on *EVERY* page. Do any logic you want here, e.g. based on the current request.
    $SEOData->title = 'Transformed Title';

    return $SEOData;
});
```

> Make sure to return the `$SEOData` object in each closure.

### Modifying tags before they are rendered

You can also **register closures that can modify the final collection of generated tags**, right before they are rendered. This is useful if you want to add custom tags to the output or if you want to modify the output of the tags.

```php
SEOManager::tagTransformer(function (TagCollection $tags): TagCollection {
    $tags = $tags->reject(fn(Tag $tag) => $tag instanceof OpenGraphTag);

    $tags->push(new MetaTag(name: 'custom-tag', content: 'My custom content'));
    // Will render: <meta name="custom-tag" content="My custom content">

    return $tags;
});
```

## Roadmap

I hope this package will be useful to you! If you have any ideas or suggestions on how to make it more useful, please let me know (rjs@ralphjsmit.com) or via the issues.

PRs are welcome, so feel free to fork and submit a pull request. I'll be happy to review your changes, think along and add them to the package.

## General

🐞 If you spot a bug, please submit a detailed issue and I'll try to fix it as soon as possible.

🔐 If you discover a vulnerability, please review [our security policy](../../security/policy).

🙌 If you want to contribute, please submit a pull request. All PRs will be fully credited. If you're unsure whether I'd accept your idea, feel free to contact me!

🙋‍♂️ [Ralph J. Smit](https://ralphjsmit.com)
