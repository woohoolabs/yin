# Woohoo Labs. Yin

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/woohoolabs/yin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/woohoolabs/yin/?branch=master)
[![Build Status](https://img.shields.io/travis/woohoolabs/yin.svg)](https://travis-ci.org/woohoolabs/yin)
[![Code Coverage](https://scrutinizer-ci.com/g/woohoolabs/yin/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/woohoolabs/yin/?branch=master)
[![Stable Release](https://img.shields.io/packagist/v/woohoolabs/yin.svg)](https://packagist.org/packages/woohoolabs/yin)
[![Downloads](https://img.shields.io/packagist/dt/woohoolabs/yin.svg)](https://packagist.org/packages/woohoolabs/yin)
[![License](https://img.shields.io/packagist/l/woohoolabs/yin.svg)](https://packagist.org/packages/woohoolabs/yin)

**Woohoo Labs. Yin is a PSR-7 compatible library for HATEOAS API-s to transform resources into JSON API format
easily and efficiently.**

## Introduction

[JSON API](http://jsonapi.org/) specification reached 1.0 on 29th May 2015 and we believe it is a big day for RESTful
API-s as this specification makes APIs more robust and future-proof than they have ever been. Woohoo Labs. Yin (named
after Yin-Yang) was born to bring efficiency and elegance for your JSON API definitions.

#### Features

- 100% PSR-7 compatible
- Developed for efficiency and ease of use
- Supports most of the JSON API specification
- Provides Documents and Transformers to fetch resources
- Provides Hydrators to create and update resources
- [Additional middlewares](https://github.com/woohoolabs/yin-middlewares) for easier kickstarting and debugging

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

When using Woohoo Labs. Yin, you will create documents and resource transformers:

There are three main types of documents in the JSON API spec, and we provide an abstract class for each (at least for
now) which you have to extend: 

- `AbstractSingleResourceDocument`: A class for single resource documents
- `AbstractCollectionDocument`: A class for collection documents
- `AbstractErrorDocument`: A class for error documents

And there is an `AbstractResourceTransformer` class for resource transformation.

#### Example resource fetch

```php
    /**
     * @param \WoohooLabs\Yin\JsonApi\JsonApi $jsonApi
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getBook(JsonApi $jsonApi)
    {
        $resource = BookRepository::getBook(1);

        $document = new BookDocument(
            new BookResourceTransformer(new AuthorResourceTransformer(), new PublisherResourceTransformer())
        );

        return $jsonApi->fetchResponse()->ok($document, $resource);
    }
```

#### Example resource creation

```php
    /**
     * @param \WoohooLabs\Yin\JsonApi\JsonApi $jsonApi
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createBook(JsonApi $jsonApi)
    {
        // Hydrating the book from the request
        $hydrator = new CreateBookHydator();
        $resource = $hydrator->hydrate($jsonApi->getRequest(), []);
        
        // Saving the newly created book

        // Creating the BookDocument to be sent as the response
        $document = new BookDocument(
            new BookResourceTransformer(new AuthorResourceTransformer(), new PublisherResourceTransformer())
        );

        // Responding with 201 Created status code and returning the new book resource
        return $jsonApi->createResponse()->created($document, $resource);
    }
```

#### How to try it out
If you want to get to know more how Yin works, have a look at the [examples](https://github.com/woohoolabs/yin/tree/master/examples):
set up a web server and visit `examples/index.php?example=EXAMPLE_NAME`, where `EXAMPLE_NAME` can be
"Book", "BookRelationships", "Users", "User" or "UserRelationships". But don't forget to run `composer install` first
in Yin's root directory. You can also restrict which fields and attributes should be fetched. The original resources -
which are transformed by Yin - can be found in the controllers.

Some example URL-s to play with:

- `examples/index.php?example=book&fields[book]=title,pages,authors,publisher&fields[author]=name&fields[publisher]=name&include=authors,publisher`
- `examples/index.php?example=users&fields[user]=firstname,lastname,contacts&fields[contact]=phone,email&include=contacts`

## Internals

Notice how attribute and relationship transformation works (e.g.:
[`BookResourceTransformer`](https://github.com/woohoolabs/yin/blob/master/examples/Book/JsonApi/Resource/BookResourceTransformer.php#L80)): 
you have to define an anonymous function for each attribute and relationship. This design allows us
to transform an attribute or a relationship only and if only it is requested. This is extremely advantageous when there
are a lot of resources to transform or a transformation is very expensive (I mean O(n<sup>2</sup>) or more).

## License

The MIT License (MIT). Please see the [License File](https://github.com/woohoolabs/yin/blob/master/LICENSE.md)
for more information.
