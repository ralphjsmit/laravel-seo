# Never worry about SEO in Laravel again!

I noticed that there weren't many SEO-packages for Laravel and that available ones are quite complex to set up and were very decoupled from the database. They only provided you with helpers to generate the tags, but you still had to use those helpers: nothing was generated automatically.

I wanted to provide a solution that allows generates valid and useful meta tags straight out-of-the-box, with limited initial configuration, whilst still providing a simple, but powerful API to work with.

If you're familiar with Spatie's media-library package, this package works in almost the same way, only then for SEO. I'm sure it will be very helpful for you, as it's usually best to SEO attention right from the beginning.

Here are a few examples of what you can do:

```php
$post = Post::find(1);

$post->addSEO();

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
    {{ seo()->for($page) }}
    
    {{-- No need to separately render a <title> tag or any other meta tags! --}}
</head>
```

It even allows you to dynamically retrieve SEO data from your Model, without having to save it manually to the SEO model. The below code will require zero additional work from you or from your users:

```php
class Post extends Model
{
    use HasSEO;
    
    public function getDynamicSEOData(): SEOData
    {
        // Override only the properties you want:
        return new SEOData(
            title: $this->title,
            description: $this->excerpt,
            image: $this->getMedia('featured_image')->first()->getPath(),
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

```
php artisan vendor:publish --tag="seo-migrations"
php artisan vendor:publish --tag="seo-config"
```

Next, go to the newly published config file in `config/seo.php` and make sure that all the settings are correct. Those settings are all sort of default values, which you'll only need to set once.

Now, add the following Blade-code on every page where you want the SEO-tags to appear:

```blade
{{ seo() }}
```

This will render a lot of sensible tags by default, already greatly improving your SEO. It will also render things like the `<title>` tag, so you don't have to render that manually.

To really profit from this package, you can associate an Eloquent model with a SEO-model. This will allow you to dynamically retrieve SEO data from your model and this package will generate as much tags as possible for you. To associate an Eloquent model with a SEO-model, add the `HasSEO` trait to your model:

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

On the SEO model, you may update the following properties:

1. `title`: this will be used for the `<title>` tag and all the related tags (OpenGraph, Twitter, etc.)
2. `description`: this will be used for the `<meta>` description tag and all the related tags (OpenGraph, Twitter, etc.)
3. `author`: this should be the name of the author and it will be used for the `<meta>` author tag and all the related tags (OpenGraph, Twitter, etc.)
4. `image`: this should be the path to the image you want to use for the `<meta>` image tag and all the related tags (OpenGraph, Twitter, etc.). The url to the image is generated via the `secure_url()` function, so be sure to check that the image is publicly available and that you provide the right path.

```php
$post = Post::find(1);

$post->seo->update([
   'title' => 'My title for the SEO tag',
   'image' => 'images/posts/1.jpg', // Will point to `/public/images/posts/1.jpg
]);
```

However, it can be a bit cumbersome to manually update the SEO-model every time you make a change. That's why I provided the `getDynamicSEOData()` method, which you can use to dynamically fetch the correct data from your own model and pass it to the SEO model:

```php
public function getDynamicSEOData(): SEOData
{
    return new SEOData(
        title: $this->title,
        description: $this->excerpt,
        author: $this->author->fullName,
    );
}
```

You are allowed to only override the properties you want and omit the other properties (or pass `null` to them). You can use the following properties:

1. `title`
2. `description`
3. `author` (should be the author's name)
4. `image` (should be the image path)
5. `url` (by default it will be `url()->current()`)
6. `enableTitleSuffix` (should be `true` or `false`, this allows you to set a suffix in the `config/seo.php` file, which will be appended to every title)
7. `site_name`
8. `published_time` (should be a `Carbon` instance with the published time. By default this will be the `created_at` property of your model)
9. `modified_time` (should be a `Carbon` instance with the published time. By default this will be the `updated_at` property of your model)
10. `section` (should be the name of the section of your content. It is used for OpenGraph article tags and it could be something like the category of the post)
11. `tags` (should be an array with tags. It is used for the OpenGraph article tags)
12. 'schema' (this should be a SchemaCollection instance, where you can configure the JSON-LD structured data schema tags)

Finally, you should update your Blade file, so that it can receive your model when generating the tags:

```blade
{{ seo()->for($page) }}
{{-- Or pass it directly to the `seo()` method: --}}
{{ seo($page ?? null) }}
```

## Generating JSON-LD structured data

This package can also generate structured data for your. At the moment only the `Article` and `BreadcrumbList` types are supported. However, you can easily send me a (draft) PR with your requested types and I'll probably add them to the package.

### Article schema markup

To enable structured data, you need to use the `schema` property of the `SEOData` class. To generate `Article` schema markup, use the `->addArticle()` method:

```php

use RalphJSmit\Laravel\SEO\SchemaCollection;

public function getDynamicSEOData(): SEOData
{
    return new SEOData(
        // ...
        schema: SchemaCollection::initialize()->addArticle(),
    );
}
```

You can pass a closure the the `->addArticle()` method to customize the individual schema markup. This closure will receive an instance of `ArticleSchema` as its argument. You can an additional author by using the `->addAuthor()` method:

```php
SchemaCollection::initialize()->addArticle(
    fn (ArticleSchema $article): ArticleSchema => $article->addAuthor('Second author')
);
```

You can completely customize the schema markup by using the `->markup()` method on the `ArticleSchema` instance:

```php
use Illuminate\Support\Collection;

SchemaCollection::initialize()->addArticle(
    function(ArticleSchema $article): ArticleSchema {
        return $article->markup(function(Collection $markup): Collection {
            return $markup->put('alternativeHeadline', $this->tagline);
        });
    }
);
```

At this point, I'm just unable to fluently support every possible version of the structured, so this is the perfect way to add an additional property to the output!

### BreadcrumbList schema markup

You can also add `BreadcrumbList` schema markup by using the `->addBreadcrumbs()` function on the `SchemaCollection`:

```php
SchemaCollection::initialize()->addBreadcrumbs(
    function(BreadcrumbListSchema $breadcrumbs): BreadcrumbListSchema {
        return $breadcrumbs->prependBreadcrumbs([
            'Homepage' => 'https://example.com',
            'Category' => 'https://example.com/test',
        ])->appendBreadcrumbs([
            'Subarticle' => 'https://example.com/test/article/2',
        ])->markup(function(Collection $markup): Collection {
            // ...
        });
    }
);
```          

This code will generate `BreadcrumbList` JSON-LD structured data with the following four pages:

1. Homepage
2. Category
3. [Current page]
4. Subarticle

## Advanced usage

Sometimes you may have advanced needs, that require you apply your own logic to the `SEOData` class, just before it is used to generate the tags. To accomplish this, you can use the `SEODataTransformer()` function on the `SEOManager` facade to register one or multiple closures that will be able to modify the `SEOData` instance at the last moment:

```php
// In the `boot()` method of a service provider somewhere
use RalphJSmit\Laravel\SEO\Facades\SEOManager;

SEOManager::SEODataTransformer(function (SEOData $SEOData): void {
    // This will change the title on every page. Do any logic you want here.
    $SEOData->title = 'Transformed Title';
});
```

### Modifying tags before they are rendered

You can also register closures that can modify the collection of generated tags, right before they are rendered. This is useful if you want to add custom tags to the output, or if you want to modify the output of the tags.

```php
SEOManager::tagTransformer(function (TagCollection $tags): TagCollection {
    $tags = $tags->reject(fn(Tag $tag) => $tag instanceof OpenGraphTag);
    
    $tags->push(new MetaTag(name: 'custom-tag', content: 'My custom content'));
    // Will render: <meta name="custom-tag" content="My custom content">
    
    return $tags;
});
```

## General

🐞 If you spot a bug, please submit a detailed issue and I'll try to fix it as soon as possible.

🔐 If you discover a vulnerability, please review [our security policy](../../security/policy).

🙌 If you want to contribute, please submit a pull request. All PRs will be fully credited. If you're unsure whether I'd accept your idea, feel free to contact me!

🙋‍♂️ [Ralph J. Smit](https://ralphjsmit.com)






