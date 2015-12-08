# Woohoo Labs. Yin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

**Woohoo Labs. Yin is a PHP framework which helps you to build beautifully crafted JSON API-s.**

We envisioned a framework of a high quality that facilitates building API-s compliant to the
JSON API specification. We wanted a framework that is able to meet every single requirement of the spec
while enabling clean application architecture and supporting domain modeling. Woohoo Labs. Yin is the
manifestation of our vision.

## Table of Contents

* [Introduction](#introduction)
    * [Features](#features)
    * [Why Yin?](#why-yin)
* [Install](#install)
* [Basic Usage](#basic-usage)
    * [Documents](#documents)
    * [Resource transformers](#resource-transformers)
    * [Hydrators](#hydrators)
    * [Exceptions](#exceptions)
    * [JsonApi class](#jsonapi-class)
* [Advanced Usage](#advanced-usage)
    * [Loading relationship data efficiently](#loading-relationship-data-efficiently)
    * [Injecting metadata into documents](#injecting-metadata-into-documents)
    * [Content negotiation](#content-negotiation)
    * [Request/response validation](#request-response-validation)
    * [Middlewares](#middlewares)
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
capabilities from content negotiation, through error handling to pagination, fetching, creation, updating and
deletion of resources. Although Yin consists of many loosely coupled packages and classes which can also be used
separately, the framework is the most powerful when it is used in its entirety.

##### Efficiency

We designed Yin to be as efficient as possible. That's why attributes and relationships are transformed only and if
only they are requested. This feature is extremely advantageous when there are a lot of resources to transform or a
rarely required transformation is very expensive. Furthermore, as transformers are stateless, the overhead of having a
separate model object for each resource is avoided. Additionally, due to statelessness, the overall library works really
well with dependency injection.

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

If you want to take advantage from request/response validation then you have to ask for the following
dependencies too:

```bash
$ composer require justinrainbow/json-schema # version 1.5.0 is tested
$ composer require seld/jsonlint # version 1.3.1 is tested
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
multiple abstract classes that help you to create your own documents for the different use cases:

- `AbstractSuccessfulDocument`: A generic base document for successful responses
- `AbstractSimpleResourceDocument`: A base class for documents about a single, very simple top-level resource
- `AbstractSingleResourceDocument`: A base class for documents about a single, more complex top-level resource
- `AbstractCollectionDocument`: A base class for documents about a collection of top-level resources

The difference between the `AbstractSimpleResourceDocument` and the `AbstractSingleResourceDocument` classes is that
the first one doesn't need a [resource transformer](#resource-transformers) so it is preferable to use for really simple
domain objects (like messages) while the latter works better for more complex domain objects (like users or addresses).

As the `AbstractSuccessfulDocument` is only useful for special use-cases (e.g. when a document can contain resources
of multiple types), we will not cover it here.

`AbstractSingleResourceDocument` and `AbstractCollectionDocument` both need a
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

Note that the `domainObject` property is a variable of any type (in this case it is a hypothetical collection),
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
    
    /* This is equivalent to the following:
    return Links::createWithBaseUri(
        "http://example.com/api",
        [
            new Link("/books/" . $this->getResourceId())
        ]
    );
}
```

This time, we want a self link to appear in the document. For this purpose, we utilize the `getResourceId()` method,
which is a shortcut of calling the resource transformer (which is introduced below) to obtain the ID of the
primary resource (`$this->transformer->getId($this->domainObject)`).

The only difference between the `AbstractSingleResourceDocument` and `AbstractCollectionDocument` is the way they
regard the `domainObject`: the first one regards it as a single domain object while the latter regards it
as an iterable collection.

###### Usage

Documents can be transformed to HTTP responses. The easiest way to achieve this is to use the
[`JsonApi` class](#jsonapi-class) and choose the appropriate response type. Successful documents support three
kinds of responses:

- normal: All the top-level members can be present in the response (except of the "errors")
- meta: Only the "jsonApi", "links" and meta top-level member can be present in the response
- relationship: The specified relationship object will be the primary data of the response

##### Documents for error responses

An `AbstractErrorDocument` can be used to create reusable documents for error responses. It also requires the same
abstract methods to be implemented as the successful ones, but additionally an `addError()` method can be used
to include error items.

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
$errorDocument->setLinks(Links::createWithoutBaseUri()->setSelf("http://example.com/api/errors/404")));
$errorDocument->addError(new MyError());
```

#### Resource transformers

Documents for successful responses can contain one or more top-level resources, an array of included resources and
resource identifier objects as relationships. That's why resource transformers are responsible to convert a
domain object into a JSON API resource or resource identifier.

Although you are encouraged to create one transformer for each resource type, there is possibility to define
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
            "",
            [
                "self" => new Link("/books/" . $this->getId($book))
            ]
        );
        
        /* This is equivalent to the following:
        return Links::createWithoutBaseUri(
            [
                "self" => new Link("/books/" . $this->getResourceId())
            ]
        );
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
    public function getDefaultIncludedRelationships($book)
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
                        Links::createWithoutBaseUri()->setSelf(new Link("/books/relationships/authors"))
                    )
                    ->setData($book["authors"], $this->authorTransformer)
                ;
            },
            "publisher" => function($book) {
                return ToOneRelationship::create()
                    ->setLinks(
                        Links::createWithoutBaseUri()->setSelf(new Link("/books/relationships/publisher"))
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

Hydrators allow us to initialize the properties of a domain object as required by the current HTTP request. This means
when a client wants to create or update a resource, hydrators can help to instantiate a domain object which can then be
validated, saved etc.

There are three abstract hydrator classes in Woohoo Labs. Yin:

- `AbstractCreateHydrator`: It can be used for requests to create a new resource
- `AbstractUpdateHydrator`: It can be used for requests to update an existing resource
- `AbstractHydrator`: It can be used for both type of requests

For the sake of brevity, we only introduce the usage of the latter class as it is simply the union of the
`AbstractCreateHydrator` and `AbstractUpdateHydrator`. Let's see how an example hydrator looks like:

```php
class BookHydator extends AbstractHydrator
{
    /**
     * Determines which resource type or types can be accepted by the hydrator.
     *
     * If the hydrator can only accept one type of resources, the method should
     * return a string. If it accepts more types, then it should return an array
     * of strings. When such a resource is received for hydration which can't be
     * accepted (its type doesn't match the acceptable type or types of the hydrator),
     * a ResourceTypeUnacceptable exception will be raised.
     *
     * @return string|array
     */
    protected function getAcceptedType()
    {
        return "book";
    }

    /**
     * Validates a client-generated ID.
     *
     * If the $clientGeneratedId is not a valid ID for the domain object, then
     * the appropriate exception should be thrown: if it is not well-formed then
     * a ClientGeneratedIdNotSupported exception can be raised, if the ID already
     * exists then a ClientGeneratedIdAlreadyExists exception can be thrown.
     *
     * @param string $clientGeneratedId
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdNotSupported
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdAlreadyExists
     * @throws \Exception
     */
    protected function validateClientGeneratedId($clientGeneratedId, RequestInterface $request, ExceptionFactoryInterface $exceptionFactory)
    {
        if ($clientGeneratedId === null) {
            throw $exceptionFactory->createClientGeneratedIdNotSupportedException($request, $clientGeneratedId);
        }
    }

    /**
     * Produces a new ID for the domain objects.
     *
     * UUID-s are preferred according to the JSON API specification.
     *
     * @return string
     */
    protected function generateId()
    {
        return Uuid::generate();
    }

    /**
     * Sets the given ID for the domain object.
     *
     * The method mutates the domain object and sets the given ID for it.
     * If it is an immutable object or an array the whole, updated domain
     * object can be returned.
     *
     * @param array $book
     * @param string $id
     * @return mixed|null
     */
    protected function setId($book, $id)
    {
        $book["id"] = $id;

        return $book;
    }

    /**
     * Provides the attribute hydrators.
     *
     * The method returns an array of attribute hydrators, where a hydrator is a key-value pair:
     * the key is the specific attribute name which comes from the request and the value is an
     * anonymous function which hydrate the given attribute.
     * These closures receive the domain object (which will be hydrated),
     * the value of the currently processed attribute and the "data" part of the request as their
     * arguments, and they should mutate the state of the domain object.
     * If it is an immutable object or an array (and passing by reference isn't used),
     * the closures should return the domain object.
     *
     * @param array $book
     * @return array
     */
    protected function getAttributeHydrator($book)
    {
        return [
            "title" => function(array $book, $attribute, $data)  { $book["title"] = $attribute; return $book; },
            "pages" => function(array &$book, $attribute, $data) { $book["pages"] = $attribute; }
        ];
    }

    /**
     * Provides the relationship hydrators.
     *
     * The method returns an array of relationship hydrators, where a hydrator is a key-value pair:
     * the key is the specific relationship name which comes from the request and the value is an
     * anonymous function which hydrate the previous relationship.
     * These closures receive the domain object (which will be hydrated),
     * an object representing the currently processed relationship (it can be a ToOneRelationship or
     * a ToManyRelationship object) and the "data" part of the request as their arguments, and they
     * should mutate the state of the domain object.
     * If it is an immutable object or an array (and passing by reference isn't used),
     * the closures should return the domain object.
     *
     * @param array $book
     * @return array
     */
    protected function getRelationshipHydrator($book)
    {
        return [
            "authors" => function(array $book, ToManyRelationship $authors, $data) {
                $book["authors"] = BookRepository::getAuthors($authors->getResourceIdentifierIds());

                return $book;
            },
            "publisher" => function(array &$book, ToOneRelationship $publisher, $data) {
                $book["publisher"] = BookRepository::getPublisher($publisher->getResourceIdentifier()->getId());
            }
        ];
    }
}
```

According to the [book example](examples/Book), the following request:

```
POST /books HTTP/1.1
Content-Type: application/vnd.api+json
Accept: application/vnd.api+json

{
  "data": {
    "type": "book",
    "attributes": {
      "title": "Continuous Delivery: Reliable Software Releases through Build, Test, and Deployment Automation",
      "pages": 512
    },
    "relationships": {
      "authors": {
        "data": [
            { "type": "author", "id": "100" },
            { "type": "author", "id": "101" }
        ]
      }
    }
  }
}
```

will result in the following `Book` domain object:

```
Array
(
    [id] => 1
    [title] => Continuous Delivery: Reliable Software Releases through Build, Test, and Deployment Automation
    [pages] => 512
    [authors] => Array
        (
            [0] => Array
                (
                    [id] => 100
                    [name] => Jez Humble
                )
            [1] => Array
                (
                    [id] => 101
                    [name] => David Farley
                )
        )
    [publisher] => Array
        (
            [id] => 12346
            [name] => Addison-Wesley Professional
        )
)
```

#### Exceptions

Woohoo Labs. Yin was designed to make error handling as easy and customizable as possible. That's why
all the default exceptions extend the `JsonApiException` class and contain
an [error document](#documents-for-error-responses) with the appropriate error object(s). That said, if you
only want to respond with an error document in case of an exception, you only need to do this:

```php
try {
    // Do something which results in an exception
} catch (JsonApiExceptionInterface $e) {
    sendResponse($e->getErrorDocument()->getResponse($response));
}
```

where `$response` is the instance of `Psr\Http\Message\ResponseInterface` and `sendResponse()` is a hypothetical
function which sends the response received in its argument.

To guarantee total customizability, we introduced the concept of __Exception Factories__. These are classes
which can create all the exceptions thrown by Woohoo Labs. Yin. As an Exception Factory of your own choice is passed to
every transformer and hydrator, you can completely customize what kind of exceptions you want to raise.

The default [Exception Factory](src/JsonApi/Exception/ExceptionFactory) creates children
of [`JsonApiException`-s](src/JsonApi/Exception) but you are free to create any type of exceptions (even the
basic `\Exception` instances). If you only want to customize the error document or the error objects of a
`JsonApiException`, just extend it and override their `createErrorDocument()` or `getErrors()` method.

#### `JsonApi` class

The `JsonApi` class is the orchestrator of the whole framework. If you want to use the entire
functionality of Woohoo Labs. Yin, it is highly recommended to utilize this class.

## Advanced Usage

This section guides you through the advanced features of Yin.

#### Loading relationship data efficiently



#### Injecting metadata into documents

#### Content negotiation

#### Request/response validation

#### Middlewares

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
    return $jsonApi->respond()->ok($document, $book);
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
    return $jsonApi->respond()->ok($document, $users);
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
    return $jsonApi->respondWithRelationship($relationshipName)->ok($document, $book);
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
    $book = $jsonApi->hydrate(new BookHydator(), []);

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
    return $jsonApi->respond()->created($document, $book);
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
    $id = $jsonApi->getRequest()->getResourceId();
    $book = BookRepository::getBook($id);

    // Hydrating the retrieved book domain object from the request
    $book = $jsonApi->hydrate(new BookHydator(), $book);
    
    // Updating the book
    // ...

    // Instantiating the book document
    $document = new BookDocument(
        new BookResourceTransformer(new AuthorResourceTransformer(), new PublisherResourceTransformer())
    );

    // Responding with "200 Ok" status code along with the book document
    return $jsonApi->respond()->ok($document, $book);
}
```

#### How to try it out
If you want to get to know more how Yin works, have a look at the
[examples](https://github.com/woohoolabs/yin/tree/master/examples): set up a web server, run `composer install` in
Yin's root directory and visit the URL-s listed below. You can restrict the retrieved fields and relationships with
the `fields` and `include` parameters as specified by JSON API.

Example URL-s for the book resources:
- `GET examples/index.php?example=book&id=1`: Fetch a book
- `GET examples/index.php?example=book-rel&id=1&rel=authors`: Fetch the authors relationship
- `GET examples/index.php?example=book-rel&id=1&rel=publisher`: Fetch the publisher relationship
- `POST examples/index.php?example=books`: Create a new book
- `PATCH examples/index.php?example=book&id=1`: Update a book

Example URL-s for the user resources:
- `GET examples/index.php?example=users`: Fetch users
- `GET examples/index.php?example=user&id=1`: Fetch a user
- `GET examples/index.php?example=user-rel&id=1&rel=contacts`: Fetch the contacts relationship

## Versioning

This library follows [SemVer v2.0.0](http://semver.org/).

## Change Log

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
