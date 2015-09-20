# Woohoo Labs. Yin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

**Woohoo Labs. Yin is a PHP framework which helps you to build beautifully crafted JSON API-s.**

We envisioned a framework of high quality that facilitates building API-s which are compliant to the
JSON API specification. We wanted a framework that is able to meet every single requirement of the spec
while enabling clean application architecture and domain modeling. Woohoo Labs. Yin is the manifestation
of our vision.

## Table of Contents

* [Introduction](#introduction)
    * [Features](#features)
    * [Why Yin?](#why-yin)
* [Install](#install)
* [Basic Usage](#basic-usage)
    * [Documents](#documents)
    * [Resource transformers](#resource-transformers)
    * [Hydrators](#hydrators)
    * [JsonApi class](#jsonapi-class)
* [Examples](#examples)
    * [Fetching a single resource](#fetching-a-single-resource)
    * [Fetching a collection of resources](#fetching-a-collection-of-resources)
    * [Fetching a relationship](#fetching-a-relationship)
    * [Creating a new resource](#creating-a-new-resource)
    * [Updating a resource](#updating-a-resource)
    * [How to try it out](#how-to-try-it-out)
* [Versioning](#versioning)
* [Change Log](#change-log)
* [Testing](#testing)
* [Contributing](#contributing)
* [Credits](#credits)
* [License](#license)

## Introduction

[JSON API](http://jsonapi.org) specification
[reached 1.0 on 29th May 2015](http://www.programmableweb.com/news/new-json-api-specification-aims-to-speed-api-development/2015/06/10)
and we also believe it is a big day for RESTful API-s as this specification makes APIs more robust and future-proof
than they have ever been. Woohoo Labs. Yin (named after Yin-Yang) was born to bring efficiency and elegance for your
JSON API server implementations.

#### Features

- 100% [PSR-7](http://www.php-fig.org/psr/psr-7/) compatibility
- 99% [JSON API 1.0](http://jsonapi.org/) compatibility (approximately)
- Developed for efficiency and ease of use
- Extensive documentation and examples
- Provides Documents and Transformers to fetch resources
- Provides Hydrators to create and update resources
- [Additional middlewares](https://github.com/woohoolabs/yin-middlewares) for the easier kickstart and debugging

#### Why Yin?

##### Complete JSON API framework

Woohoo Labs. Yin is a framework-agnostic library which supports the full JSON API specification: it provides various
capabilities for content negotiation, error handling, pagination, fetch, creation, update, deletion of resources.
Although Yin consists of many loosely coupled packages and classes which can also be used separately, but the
framework is the most powerful when it is used in its entirety.

##### Efficiency

We designed the transformation processes so that attributes and relationships are transformed only and if only they
are requested. This feature is extremely advantageous when there are a lot of resources to transform or a rarely
required transformation is very expensive.

##### Supplementary middlewares

[There are some additional middlewares](https://github.com/woohoolabs/yin-middlewares) for Woohoo Labs. Yin you might
find useful: they can facilitate various tasks like error handling (via transformation of exceptions into JSON API
error messages), dispatching JSON API-aware controllers or debugging (via synthax checking and validation of requests
and responses).

## Install

You need [Composer](https://getcomposer.org) to install this library. Run the command below and you will get the latest
version:

```bash
$ composer require woohoolabs/yin
```

## Basic Usage

When using Woohoo Labs. Yin, you will create:
- documents and resource transformers in order to map domain objects to JSON API responses
- hydrators in order to transform resources in a POST or PATCH request to domain objects

Furthermore, a `JsonApi` class will be responsible for the instrumentation, while a PSR-7 compatible
`Request` class provides functionalities you commonly need.

#### Documents

The following sections will guide you through how to create documents for successful responses and
how to create or build error documents.

##### Documents for successful responses

For successful requests, you have to return information about one or more resources. Woohoo Labs. Yin provides
three abstract classes that help you to create your own documents for the different use cases:

- `AbstractSuccessfulDocument`: A generic base document for successful responses
- `AbstractSingleResourceDocument`: A base class for documents about a single top-level resource
- `AbstractCollectionDocument`: A base class for documents about a collection of top-level resources

As the `AbstractSuccessfulDocument` is only useful for special use-cases (e.g. when a document can contain resources
of multiple types), we will not cover it here.

`AbstractSingleResourceDocument` or `AbstractCollectionDocument` both need a
[resource transformer](#resource-transformers) to work, which is a concept introduced in the following sections.
For now, it is enough to know that one must be passed for the documents during instantiation. This means that a
minimal constructor of your documents must look like this:

```php
/**
 * @param MyResourceTransformer $transformer
 */
public function __construct(MyResourceTransformer $transformer)
{
    parent::__construct($transformer);
}
```

When you extend either `AbstractSingleResourceDocument` or `AbstractCollectionDocument`, they both require
you to implement the following methods:

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
[profile](http://jsonapi.org/extensions/#profiles) and some information about pagination to the document.

Note that the `domainObject` property is a variable of any type (in this case it is an imaginary collection),
and this is the main "subject" of the document.

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
        "http://example.com/api",
        [
            "self" => new Link("/books/" . $this->getResourceId())
        ]
    );
    
    // This is equivalent to the following:
    // return Links::createRelativeWithSelf("http://example.com/api", "/books/" . $this->getResourceId());
    // or:
    // return Links::createAbsoluteWithSelf("http://example.com/api/books/" . $this->getResourceId());
}
```

This time, we want a self link to appear in the document. For this purpose, we utilize the `getResourceId()` method,
which is a shortcut of calling the resource transformer (which is introduced below) to obtain the ID of the
primary resource (`$this->transformer->getId($this->domainObject)`).

The only difference between `AbstractSingleResourceDocument` and `AbstractCollectionDocument` lies in the way they
regard the `domainObject`: the first one regards it as a single domain object while the latter regards it
as an iterable collection of domain objects.

###### Usage

Documents are to be transformed to HTTP responses. The easiest way to achieve this is to use the
[`JsonApi` class](#jsonapi-class) and chose the appropriate response type. Successful documents support three
kinds of responses:

- normal: All the top-level members can be present in the response
- meta: Only the meta top-level member will be present in the response
- relationship: Only the specified relationship object will be present in the response

##### Documents for error responses

An `AbstractErrorDocument` can be used to create reusable documents for error responses. It also requires the same
abstract methods to be implemented as the successful ones, but additionally an `addError()` method  can be used
to include error items to it.

```php
/** @var AbstractErrorDocument $errorDocument */
$errorDocument = new MyErrorDocument();
$errorDocument->addError(new MyError());
```

There is an `ErrorDocument` too, which makes it possible to build error responses on-the-fly:

```php
/** @var ErrorDocument $errorDocument */
$errorDocument = new ErrorDocument();
$errorDocument->setJsonApi(new JsonApi("1.0"));
$errorDocument->setLinks(Links::createAbsoluteWithSelf("http://example.com/api/errors/404")));
$errorDocument->addError(new MyError());
```

#### Resource transformers

Documents for successful responses can contain one or more top-level resources, an array of included resources and
resource identifier objects as relationships. That's why resource transformers are responsible to convert a
domain object into a JSON API resource or resource identifier.

Although you are encouraged to create one transformer for each resource types, there is possibility to define
"composite" resource transformers too following the Composite design pattern if you need more sophistication.

Resource transformers must implement the `ResourceTransformerInterface`, but to facilitate this job, you can extend
the `AbstractResourceTransformer` class too.

Children of the `AbstractResourceTransformer` class need several abstract methods to be implemented, most of which
are the same as it was seen at the documents. The following example illustrates a resource transformer dealing with
a book domain object and its "authors" and "publisher" relationships.

```php
class BookResourceTransformer extends AbstractResourceTransformer
{
    /**
     * @var \WoohooLabs\Yin\Examples\Book\JsonApi\Resource\AuthorResourceTransformer
     */
    private $authorTransformer;

    /**
     * @var \WoohooLabs\Yin\Examples\Book\JsonApi\Resource\PublisherResourceTransformer
     */
    private $publisherTransformer;

    /**
     * @param \WoohooLabs\Yin\Examples\Book\JsonApi\Resource\AuthorResourceTransformer $authorTransformer
     * @param \WoohooLabs\Yin\Examples\Book\JsonApi\Resource\PublisherResourceTransformer $publisherTransformer
     */
    public function __construct(
        AuthorResourceTransformer $authorTransformer,
        PublisherResourceTransformer $publisherTransformer
    ) {
        $this->authorTransformer = $authorTransformer;
        $this->publisherTransformer = $publisherTransformer;
    }

    /**
     * Provides information about the "type" section of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $book
     * @return string
     */
    public function getType($book)
    {
        return "book";
    }

    /**
     * Provides information about the "id" section of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $book
     * @return string
     */
    public function getId($book)
    {
        return $book["id"];
    }

    /**
     * Provides information about the "meta" section of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the section won't appear in the response.
     *
     * @param array $book
     * @return array
     */
    public function getMeta($book)
    {
        return [];
    }

    /**
     * Provides information about the "links" section of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $book
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($book)
    {
        return new Links(
            "http://example.com/api"
            [
                "self" => new Link("/books/" . $this->getId($book))
            ]
        );
        
        // This is equivalent to the following:
        // return Links::createRelativeWithSelf("http://example.com/api", "/books/" . $this->getResourceId());
        // or:
        // return Links::createAbsoluteWithSelf("http://example.com/api/books/" . $this->getResourceId());
    }

    /**
     * Provides information about the "attributes" section of the current resource.
     *
     * The method returns an array where the keys signify the attribute names,
     * while the values are closures receiving the domain object as an argument,
     * and they should return the value of the corresponding attribute.
     *
     * @param array $book
     * @return array
     */
    public function getAttributes($book)
    {
        return [
            "title" => function(array $book) { return $book["title"]; },
            "pages" => function(array $book) { return $this->toInt($book["pages"]); },
        ];
    }
    
    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $book
     * @return array
     */
    public function getDefaultRelationships($book)
    {
        return ["authors"];
    }

    /**
     * Provides information about the "relationships" section of the current resource.
     *
     * The method returns an array where the keys signify the relationship names,
     * while the values are closures receiving the domain object as an argument,
     * and they should return a new relationship instance (to-one or to-many).
     *
     * @param array $book
     * @return array
     */
    public function getRelationships($book)
    {
        return [
            "authors" => function(array $book) {
                return ToManyRelationship::create()
                    ->setLinks(
                        Links::createAbsoluteWithSelf(new Link("http://example.com/api/books/relationships/authors"))
                    )
                    ->setData($book["authors"], $this->authorTransformer)
                ;
            },
            "publisher" => function($book) {
                return ToOneRelationship::create()
                    ->setLinks(
                        Links::createAbsoluteWithSelf(new Link("http://example.com/api/books/relationships/publisher"))
                    )
                    ->setData($book["publisher"], $this->publisherTransformer)
                ;
            }
        ];
    }
}
```

Generally, you don't use resource transformers directly. Only documents need them to be able to fill the "data",
the "included" and the "relationship" sections in the responses.

#### Hydrators

#### `JsonApi` class

## Examples

#### Fetching a single resource

```php
/**
 * @param \WoohooLabs\Yin\JsonApi\JsonApi $jsonApi
 * @return \Psr\Http\Message\ResponseInterface
 */
public function getBook(JsonApi $jsonApi)
{
    // Getting the "id" of the currently requested book
    $id = $jsonApi->getRequest()->getAttribute("id");

    // Retrieving a book domain object with an ID of $id
    $book = BookRepository::getBook($id);

    // Instantiating a book document
    $document = new BookDocument(
        new BookResourceTransformer(
            new AuthorResourceTransformer(),
            new PublisherResourceTransformer()
        )
    );

    // Responding with "200 Ok" status code along with the book document
    return $jsonApi->fetchResponse()->ok($document, $book);
}
```

#### Fetching a collection of resources

```php
/**
 * @param \WoohooLabs\Yin\JsonApi\JsonApi $jsonApi
 * @return \Psr\Http\Message\ResponseInterface
 */
public function getUsers(JsonApi $jsonApi)
{
    // Extracting pagination information from the request, page = 1, size = 10 if it is missing
    $pagination = $jsonApi->getRequest()->getPageBasedPagination(1, 10);

    // Fetching a paginated collection of user domain objects
    $users = UserRepository::getUsers($pagination->getPage(), $pagination->getSize());

    // Instantiating a users document
    $document = new UsersDocument(new UserResourceTransformer(new ContactResourceTransformer()));

    // Responding with "200 Ok" status code along with the users document
    return $jsonApi->fetchResponse()->ok($document, $users);
}
```

#### Fetching a relationship

```php
/**
 * @param \WoohooLabs\Yin\JsonApi\JsonApi $jsonApi
 * @return \Psr\Http\Message\ResponseInterface
 */
public function getBookRelationships(JsonApi $jsonApi)
{
    // Getting the "id" of the currently requested book
    $id = $jsonApi->getRequest()->getAttribute("id");
    
    // Getting the currently requested relationship's name
    $relationshipName = $jsonApi->getRequest()->getAttribute("relationship");
    
    // Retrieving a book domain object with an ID of $id
    $book = BookRepository::getBook($id);

    // Instantiating a book document
    $document = new BookDocument(
        new BookResourceTransformer(new AuthorResourceTransformer(), new PublisherResourceTransformer())
    );

    // Responding with "200 Ok" status code along with the requested relationship document
    return $jsonApi->fetchRelationshipResponse($relationshipName)->ok($document, $book);
}
```

#### Creating a new resource

```php
/**
 * @param \WoohooLabs\Yin\JsonApi\JsonApi $jsonApi
 * @return \Psr\Http\Message\ResponseInterface
 */
public function createBook(JsonApi $jsonApi)
{
    // Hydrating a new book domain object from the request
    $hydrator = new CreateBookHydator();
    $book = $hydrator->hydrate($jsonApi->getRequest(), []);

    // Saving the newly created book
    // ...

    // Creating the book document to be sent as the response
    $document = new BookDocument(
        new BookResourceTransformer(
            new AuthorResourceTransformer(), 
            new PublisherResourceTransformer()
        )
    );

    // Responding with "201 Created" status code along with the book document
    return $jsonApi->createResponse()->created($document, $book);
}
```

#### Updating a resource

```php
/**
 * @param \WoohooLabs\Yin\JsonApi\JsonApi $jsonApi
 * @return \Psr\Http\Message\ResponseInterface
 */
public function updateBook(JsonApi $jsonApi)
{
    // Retrieving a book domain object with an ID of $id
    $id = $jsonApi->getRequest()->getBodyDataId();
    $book = BookRepository::getBook($id);

    // Hydrating the retrieved book domain object from the request
    $hydrator = new BookHydator();
    $book = $hydrator->hydrate($jsonApi->getRequest(), $book);

    // Instantiating the user document
    $document = new BookDocument(
        new BookResourceTransformer(new AuthorResourceTransformer(), new PublisherResourceTransformer())
    );

    // Responding with "200 Ok" status code along with the book document
    return $jsonApi->updateResponse()->ok($document, $book);
}
```

#### How to try it out
If you want to get to know more how Yin works, have a look at the
[examples](https://github.com/woohoolabs/yin/tree/master/examples): set up a web server, run `composer install` in
Yin's root directory and visit the URL-s listed below. You can restrict the retrieved fields and relationships with
the `fields` and `include` parameters as specified by JSON API.

Example URL-s for the book resources:
- `GET examples/index.php?example=books&id=1`: Fetch a book
- `GET examples/index.php?example=books-rel&id=1&rel=authors`: Fetch the authors relationship
- `GET examples/index.php?example=books-rel&id=1&rel=publisher`: Fetch the publisher relationship
- `POST examples/index.php?example=books`: Create a new book
- `PATCH examples/index.php?example=books&id=1`: Update a book

Example URL-s for the user resources:
- `GET examples/index.php?example=users`: Fetch users
- `GET examples/index.php?example=users&id=1`: Fetch a user
- `GET examples/index.php?example=users-rel&id=1&rel=contacts`: Fetch the contacts relationship

## Versioning

This library follows [SemVer v2.0.0](http://semver.org/).

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

Woohoo Labs. Yin has a PHPUnit test suite. To run the tests, run the following command from the project folder
after you have copied phpunit.xml.dist to phpunit.xml:

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Máté Kocsis][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/woohoolabs/yin.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-travis]: https://img.shields.io/travis/woohoolabs/yin/master.svg
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/woohoolabs/yin.svg
[ico-code-quality]: https://img.shields.io/scrutinizer/g/woohoolabs/yin.svg
[ico-downloads]: https://img.shields.io/packagist/dt/woohoolabs/yin.svg

[link-packagist]: https://packagist.org/packages/woohoolabs/yin
[link-travis]: https://travis-ci.org/woohoolabs/yin
[link-scrutinizer]: https://scrutinizer-ci.com/g/woohoolabs/yin/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/woohoolabs/yin
[link-downloads]: https://packagist.org/packages/woohoolabs/yin
[link-author]: https://github.com/kocsismate
[link-contributors]: ../../contributors
