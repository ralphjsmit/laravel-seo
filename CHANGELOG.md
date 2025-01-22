# Changelog

All notable changes to `laravel-seo` will be documented in this file.

## 1.6.7 - 2025-01-22

- Fix: prevent Livewire from injecting morph markers into tag view.

## 1.6.6 - 2025-01-22

- Fix: incorrect Blade closing `@unless` tag.

## 1.6.5 - 2025-01-20

- Feat: automatic detection of Inertia routes and addition of `inertia` attribute to `<title>` tag in #89by @touqeershafi.

## 1.6.4 - 2024-12-06

- Fix: do not check for image file existence if path is url.

## 1.6.3 - 2024-08-30

- Fix: potential preventAccessingMissingAttributes() exception if someone had old database migration.

## 1.6.2 - 2024-06-15

- Fix: do also not escape image URLs for OpenGraph and Twitter.

## 1.6.1 - 2024-06-15

- Fix: duplicate Twitter cards issue (#83).
- Fix: image URL escaping (#84).

## 1.6.0 - 2024-06-15

- Feat: add support for alternate links in (#78)
- Feat: fluent support for FaqPage schema (#77)
- Feat: refactor JSON+LD schema & allow any type of custom schema (#81)

## 1.5.1 - 2024-05-04

- Fix: inconsistency with escaping.

## 1.5.0 - 2024-03-14

- Laravel 11 compatibility.

## 1.4.5 - 2024-02-29

- Update: make article `datePublished` and `dateModified` optional, since it's only recommended properties.

## 1.4.4 - 2024-02-07

- Fix: issue with Livewire morph markers.

## 1.4.3 - 2023-12-20

- Allow overriding custom OpenGraph titles in #53.

## 1.4.2 - 2023-10-30

- Fix locale casing in cases of locales like `en_US`.

## 1.4.1 - 2023-08-12

- Add support for immutable timestamps in #43 by @standaniels

## 1.3.0 - 2023-02-17

- Add Laravel 10 support in #30

## 1.2.2 - 2022-11-21

- Add `down()` method to migration.

## 1.2.1 - 2022-10-06

– Fix issue with incorrect key in ArticleSchema.

## 1.2.0 - 2022-09-27

– Add full support for robots tags.

## 1.1.0 - 2022-09-19

– Support passing SEOData directly from the controller to the layout file.

## 1.0.4 - 2022-06-01

– Fix: remove accidental `dump()`.

## 1.0.3 - 2022-06-01

– Feat: support use of models without the related SEO-model in the database (#5).

## 1.0.2 - 2022-06-01

– Fix: OpenGraph specification
– Fix: using the ->imageMeta with a custom override URL.

## 1.0.1 - 2022-05-24

– Fix incorrect import #9.

## 0.7.0 - 2022-04-15

- Dynamic SEO model.

## 0.6.1 - 2022-04-06

- Fallback for models without ->seo.

## 0.6.0 - 2022-03-16

- Add support for sitemap tags.

## 0.5.3 - 2022-03-09

- Add support for canonical URLs.
- Refactor `$SEOData->url` resolution if we should get it from the current url.

## 0.5.2 - 2022-03-09

- Add support for image sizes on Twitter cards

## 0.5.1 - 2022-03-09

- Fix case where image size wasn't retrieved when it could be retrieved

## 0.5.0 - 2022-03-09

- Update implementation for handling of image paths: we now only accept public paths.

## 0.4.0 - 2022-03-04

- Add support for automatic `og:locale`

## 0.3.1 - 2022-02-17

- Use `https` for `@context` reference to schema.org.

## 0.3.0 - 2022-02-10

- Feat: separate title for the homepage.

## 0.2.0 - 2022-02-08

- Add articleBody

## 0.1.3 - 2022-02-08

- Fix migration name

## 0.1.2 - 2022-02-08

- Fix service provider name

## 0.1.1 - 2022-02-08

- Fix service provider namespace
