# Woohoo Labs. Yin

[![Build Status](https://img.shields.io/travis/woohoolabs/yin.svg)](https://travis-ci.org/woohoolabs/yin)
[![Code Coverage](https://scrutinizer-ci.com/g/woohoolabs/yin/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/woohoolabs/yin/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/woohoolabs/yin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/woohoolabs/yin/?branch=master)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/2e1d0616-e47a-4ae7-bfed-07dec4d29d5f.svg)](https://insight.sensiolabs.com/projects/2e1d0616-e47a-4ae7-bfed-07dec4d29d5f)
[![Stable Release](https://img.shields.io/packagist/v/woohoolabs/yin.svg)](https://packagist.org/packages/woohoolabs/yin)
[![License](https://img.shields.io/packagist/l/woohoolabs/yin.svg)](https://packagist.org/packages/woohoolabs/yin)

**Woohoo Labs. Yin is a PSR-7 compatible PHP library for HATEOAS API-s.**

Our aim was to create an elegant framework which helps you to build beautifully crafted HATEOAS API-s.
Currently, Woohoo labs. Yin only supports the JSON API specification.

## Introduction

[JSON API](http://jsonapi.org) specification reached 1.0 on 29th May 2015 and we believe it is a big day for RESTful
API-s as this specification makes APIs more robust and future-proof than they have ever been. Woohoo Labs. Yin (named
after Yin-Yang) was born to bring efficiency and elegance for your JSON API server implementations.

## Features

- 100% [PSR-7](http://www.php-fig.org/psr/psr-7/) compatibility
- 99% [JSON API](http://jsonapi.org/) compatibility (approximately)
- Developed for efficiency and ease of use
- Extensive documentation and examples
- Provides Documents and Transformers to fetch resources
- Provides Hydrators to create and update resources
- [Additional middlewares](https://github.com/woohoolabs/yin-middlewares) for the easier kickstart and debugging

## Install

The steps of this process are quite straightforward. The only thing you need is [Composer](http://getcomposer.org).

#### Add Yin to your composer.json:

To install this library, run the command below and you will get the latest version:

```bash
$ composer require woohoolabs/yin
```

#### Autoload in your bootstrap:

```php
require "vendor/autoload.php"
```

## Basic Usage

**Important:** Before learning about Woohoo Labs. Yin, please make sure you understand at least the basic concepts
of the [JSON API specification](http://jsonapi.org).

When using Woohoo Labs. Yin, you will create:
- documents and resource transformers in order to map domain objects to JSON API responses
- hydrators in order to transform created or updated JSON API resources to domain objects

And a `JsonApi` class will be responsible for the instrumentation.

#### Documents

The JSON API spec differentiates three main types of documents: documents containing information about a resource,
documents containing information about a collection of resources and error documents. Woohoo Labs. Yin
provides an abstract class for each use-case which you have to extend.

##### Documents for successful responses

Depending on the cardinality of the resources to be retrieved, you can extend either `AbstractSingleResourceDocument` or
`AbstractCollectionDocument`. They have the same abstract methods which you have to implement:

```php
/**
 * Provides information about the "jsonApi" section of the current document.
 *
 * The method returns a new JsonApi schema object if this section should be present or null
 * if it should be omitted from the response.
 *
 * @return \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null
 */
public function getJsonApi()
{
    return new JsonApi("1.0");
}
```

The description says it very clear: if you want a jsonApi section in your response, then create a new `JsonApi` object.
Its constructor expects the JSON API version number and an optional meta object (as an array).

```php
/**
 * Provides information about the "meta" section of the current document.
 *
 * The method returns an array of non-standard meta information about the document. If
 * this array is empty, the section won't appear in the response.
 *
 * @return array
 */
public function getMeta()
{
    return [
        "profile" => "http://api.example.com/profile",
        "page" => [
            "offset" => $this->domainObject->getOffset(),
            "limit" => $this->domainObject->getLimit(),
            "total" => $this->domainObject->getCount()
        ] 
    ];
}
```

Documents can also have a meta section which can contain any non-standard information. The example above adds a
[profile](http://jsonapi.org/extensions/#profiles) to the document.

Note that the `domainObject` property is a variable of any type (in this case it is an imaginary collection),
which will be transformed into the primary resource. Important to know that it is lazily instantiated:
it takes place when the transformation starts (hence just before these abstract methods are invoked).

```php
/**
 * Provides information about the "links" section of the current document.
 *
 * The method returns a new Links schema object if you want to provide linkage data
 * for the document or null if the section should be omitted from the response.
 *
 * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
 */
public function getLinks()
{
    return new Links(
        [
            "self" => new Link("http://example.com/api/books/" . $this->transformer->getId($this->domainObject))
        ]
    );
}
```

This time, we want a self link to appear in the document. For this purpose, we utilize the resource transformer
to obtain the ID of the primary resource.

The difference between the `AbstractSingleResourceDocument` and the `AbstractCollectionDocument` lies in the way they
regard the `domainObject`: the first one regards it as a single entity while the latter regards it
as an iterable collection of entities.

##### Documents for error responses

- `AbstractErrorDocument`: It can be used to create reusable error responses
- `ErrorDocument`: It can be used to build error responses on-the-fly

#### Transformers

There is an `AbstractResourceTransformer` class for resource transformation.

#### `JsonApi` class

#### Examples

##### Example resource fetching

```php
/**
 * @param \WoohooLabs\Yin\JsonApi\JsonApi $jsonApi
 * @return \Psr\Http\Message\ResponseInterface
 */
public function getBook(JsonApi $jsonApi)
{
    $book = BookRepository::getBook(1);

    $document = new BookDocument(
        new BookResourceTransformer(
            new AuthorResourceTransformer(),
            new PublisherResourceTransformer()
        )
    );

    return $jsonApi->fetchResponse()->ok($document, $book);
}
```

##### Example resource creation

```php
/**
 * @param \WoohooLabs\Yin\JsonApi\JsonApi $jsonApi
 * @return \Psr\Http\Message\ResponseInterface
 */
public function createBook(JsonApi $jsonApi)
{
    // Hydrating the book from the request
    $hydrator = new CreateBookHydator();
    $book = $hydrator->hydrate($jsonApi->getRequest(), []);

    // Saving the newly created book

    // Creating the BookDocument to be sent as the response
    $document = new BookDocument(
        new BookResourceTransformer(
            new AuthorResourceTransformer(), 
            new PublisherResourceTransformer()
        )
    );

    // Responding with 201 Created status code and returning the new book resource
    return $jsonApi->createResponse()->created($document, $book);
}
```

#### How to try it out
If you want to get to know more how Yin works, have a look at the [examples](https://github.com/woohoolabs/yin/tree/master/examples):
set up a web server and visit `examples/index.php?example=EXAMPLE_NAME`, where `EXAMPLE_NAME` can be
"Book", "BookRelationships", "Users", "User" or "UserRelationships". But don't forget to run `composer install` first
in Yin's root directory. You can also restrict which fields and attributes should be fetched. The original resources -
which are transformed by Yin - can be found in the controllers.

Some example URL-s to play with:

- `examples/index.php?example=book&id=1&fields[book]=title,pages,authors,publisher&fields[author]=name&fields[publisher]=name&include=authors,publisher`
- `examples/index.php?example=users&fields[user]=firstname,contacts&fields[contact]=phone_number,email&include=contacts`

## Internals

Notice how attribute and relationship transformation works (e.g.:
[`BookResourceTransformer`](https://github.com/woohoolabs/yin/blob/master/examples/Book/JsonApi/Resource/BookResourceTransformer.php#L80)): 
you have to define an anonymous function for each attribute and relationship. This design allows us
to transform an attribute or a relationship only and if only it is requested. This is extremely advantageous when there
are a lot of resources to transform or a transformation is very expensive (I mean O(n<sup>2</sup>) or more).

## License

The MIT License (MIT). Please see the [License File](https://github.com/woohoolabs/yin/blob/master/LICENSE.md)
for more information.
