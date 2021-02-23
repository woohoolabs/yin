# Woohoo Labs. Yin

[![Latest Version on Packagist][ico-version]][link-version]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-build]][link-build]
[![Coverage Status][ico-coverage]][link-coverage]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
[![Gitter][ico-support]][link-support]

**Woohoo Labs. Yin is a PHP framework which helps you to build beautifully crafted JSON:APIs.**

## Table of Contents

* [Introduction](#introduction)
    * [Features](#features)
    * [Why Yin?](#why-yin)
* [Install](#install)
* [Basic Usage](#basic-usage)
    * [Documents](#documents)
    * [Resources](#resources)
    * [Hydrators](#hydrators)
    * [Exceptions](#exceptions)
    * [JsonApi class](#jsonapi-class)
    * [JsonApiRequest class](#jsonapirequest-class)
* [Advanced Usage](#advanced-usage)
    * [Pagination](#pagination)
    * [Loading relationship data efficiently](#loading-relationship-data-efficiently)
    * [Injecting metadata into documents](#injecting-metadata-into-documents)
    * [Content negotiation](#content-negotiation)
    * [Request/response validation](#requestresponse-validation)
    * [Custom serialization](#custom-serialization)
    * [Custom deserialization](#custom-deserialization)
    * [Middleware](#middleware)
* [Examples](#examples)
    * [Fetching a single resource](#fetching-a-single-resource)
    * [Fetching a collection of resources](#fetching-a-collection-of-resources)
    * [Fetching a relationship](#fetching-a-relationship)
    * [Creating a new resource](#creating-a-new-resource)
    * [Updating a resource](#updating-a-resource)
    * [How to try it out](#how-to-try-it-out)
* [Integrations](#integrations)
* [Versioning](#versioning)
* [Change Log](#change-log)
* [Testing](#testing)
* [Contributing](#contributing)
* [Support](#support)
* [Credits](#credits)
* [License](#license)

## Introduction

[JSON:API](https://jsonapi.org) specification
[reached 1.0 on 29th May 2015](https://www.programmableweb.com/news/new-json-api-specification-aims-to-speed-api-development/2015/06/10)
and we also believe it is a big day for RESTful APIs as this specification can help you make APIs more robust and
future-proof. Woohoo Labs. Yin (named after Yin-Yang) was born to bring efficiency and elegance to your JSON:API
servers, while [Woohoo Labs. Yang](https://github.com/woohoolabs/yang) is its client-side counterpart.

### Features

- 100% [PSR-7](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md) compatibility
- 99% [JSON:API 1.1](https://jsonapi.org/) compatibility (approximately)
- Developed for efficiency and ease of use
- Extensive documentation and examples
- Provides Documents and Transformers to fetch resources
- Provides Hydrators to create and update resources
- [Additional middleware](https://github.com/woohoolabs/yin-middleware) for the easier kickstart and debugging

### Why Yin?

#### Complete JSON:API framework

Woohoo Labs. Yin is a framework-agnostic library which supports the vast majority of the JSON:API 1.1 specification:
it provides various capabilities including content negotiation, error handling and pagination, as well as fetching,
creation, updating and deleting resources. Although Yin consists of many loosely coupled packages and classes which
can be used separately, the framework is most powerful when used in its entirety.

#### Efficiency

We designed Yin to be as efficient as possible. That's why attributes and relationships are transformed only and if
only they are requested. This feature is extremely advantageous when there are a lot of resources to transform or a
rarely required transformation is very expensive. Furthermore, as transformers are stateless, the overhead of having a
separate model object for each resource is avoided. Additionally, due to statelessness, the overall library works really
well with dependency injection.

#### Supplementary middleware

There is some [additional middleware](https://github.com/woohoolabs/yin-middleware) for Woohoo Labs. Yin you might
find useful. It can facilitate various tasks like error handling (via transformation of exceptions into JSON:API
error responses), dispatching JSON:API-aware controllers or debugging (via syntax checking and validation of requests
and responses).

## Install

The only thing you need before getting started is [Composer](https://getcomposer.org).

### Install a PSR-7 implementation:

Because Yin requires a PSR-7 implementation (a package which provides the `psr/http-message-implementation` virtual
package), you must install one first. You may use [Zend Diactoros](https://github.com/zendframework/zend-diactoros) or
any other library of your preference:

```bash
$ composer require zendframework/zend-diactoros
```

### Install Yin:

To install the latest version of this library, run the command below:

```bash
$ composer require woohoolabs/yin
```

> Note: The tests and examples won't be downloaded by default. You have to use `composer require woohoolabs/yin --prefer-source`
or clone the repository if you need them.

The latest version of Yin requires PHP 7.1 at least but you can use Yin 2.0.6 for PHP 7.0.

### Install the optional dependencies:

If you want to take advantage of request/response validation then you have to also ask for the following
dependencies:

```bash
$ composer require justinrainbow/json-schema
$ composer require seld/jsonlint
```

## Basic Usage

When using Woohoo Labs. Yin, you will create:
- Documents and resources in order to map domain objects to JSON:API responses
- Hydrators in order to transform resources in a POST or PATCH request to domain objects

Furthermore, a [`JsonApi`](#jsonapi-class) class will be responsible for the instrumentation, while a PSR-7 compatible
[`JsonApiRequest`](#jsonapirequest-class) class provides functionalities you commonly need.

### Documents

The following sections will guide you through creating documents for successful responses and
creating or building error documents.

#### Documents for successful responses

For successful requests, you must return information about one or more resources. Woohoo Labs. Yin provides
multiple abstract classes that help you to create your own documents for different use cases:

- `AbstractSuccessfulDocument`: A generic base document for successful responses
- `AbstractSimpleResourceDocument`: A base class for documents about a single, very simple top-level resource
- `AbstractSingleResourceDocument`: A base class for documents about a single, more complex top-level resource
- `AbstractCollectionDocument`: A base class for documents about a collection of top-level resources

As the `AbstractSuccessfulDocument` is only useful for special use-cases (e.g. when a document can contain resources
of multiple types), we will not cover it here.

The difference between the `AbstractSimpleResourceDocument` and the `AbstractSingleResourceDocument` classes is that
the first one doesn't need a [resource](#resource) object. For this reason, it is preferable to use
the former for only really simple domain objects (like messages), while the latter works better for more complex domain
objects (like users or addresses).

Let's first have a quick look at the `AbstractSimpleResourceDocument`: it has a `getResource()` abstract method which
needs to be implemented when you extend this class. The `getResource()` method returns the whole transformed resource as
an array including the type, id, attributes, and relationships like below:

```php
protected function getResource(): array
{
    return [
        "type"       => "<type>",
        "id"         => "<ID>",
        "attributes" => [
            "key" => "value",
        ],
    ];
}
```

> Please note that `AbstractSimpleResourceDocument` doesn't support some features out-of-the-box like sparse fieldsets,
automatic inclusion of related resources etc. That's why this document type should only be considered as a quick-and-dirty
solution, and generally you should choose another, more advanced document type introduced below in the majority of the use cases.

`AbstractSingleResourceDocument` and `AbstractCollectionDocument` both need a [resource](#resource) object in order to work,
which is a concept introduced in the following sections. For now, it is enough to know that one must be passed for the documents
during instantiation. This means that a minimal constructor of your documents should look like this:

```php
public function __construct(MyResource $resource)
{
    parent::__construct($resource);
}
```

You can of course provide other dependencies for your constructor or completely omit it if you don't need it.

When you extend either `AbstractSingleResourceDocument` or `AbstractCollectionDocument`, they both require
you to implement the following methods:

```php
/**
 * Provides information about the "jsonapi" member of the current document.
 *
 * The method returns a new JsonApiObject object if this member should be present or null
 * if it should be omitted from the response.
 */
public function getJsonApi(): ?JsonApiObject
{
    return new JsonApiObject("1.1");
}
```

The description says it very clear: if you want a `jsonapi` member in your response, then create a new `JsonApiObject`.
Its constructor expects the JSON:API version number and an optional meta object (as an array).

```php
/**
 * Provides information about the "meta" member of the current document.
 *
 * The method returns an array of non-standard meta information about the document. If
 * this array is empty, the member won't appear in the response.
 */
public function getMeta(): array
{
    return [
        "page" => [
            "offset" => $this->object->getOffset(),
            "limit" => $this->object->getLimit(),
            "total" => $this->object->getCount(),
        ]
    ];
}
```

Documents may also have a "meta" member which can contain any non-standard information. The example above adds
information about pagination to the document.

Note that the `object` property is a variable of any type (in this case it is a hypothetical collection),
and this is the main "subject" of the document.

```php
/**
 * Provides information about the "links" member of the current document.
 *
 * The method returns a new DocumentLinks object if you want to provide linkage data
 * for the document or null if the member should be omitted from the response.
 */
public function getLinks(): ?DocumentLinks
{
    return new DocumentLinks(
        "https://example.com/api",
        [
            "self" => new Link("/books/" . $this->getResourceId())
        ]
    );

    /* This is equivalent to the following:
    return DocumentLinks::createWithBaseUri(
        "https://example.com/api",
        [
            "self" => new Link("/books/" . $this->getResourceId())
        ]
    );
}
```

This time, we want a self link to appear in the document. For this purpose, we utilize the `getResourceId()` method,
which is a shortcut of calling the resource (which is introduced below) to obtain the ID of the
primary resource (`$this->resource->getId($this->object)`).

The only difference between the `AbstractSingleResourceDocument` and `AbstractCollectionDocument` is the way they
regard the `object`. The first one regards it as a single domain object while the latter regards it
as an iterable collection.

##### Usage

Documents can be transformed to HTTP responses. The easiest way to achieve this is to use the
[`JsonApi` class](#jsonapi-class) and choose the appropriate response type. Successful documents support three
kinds of responses:

- normal: All the top-level members can be present in the response (except for the "errors")
- meta: Only the "jsonapi", "links" and meta top-level member can be present in the response
- relationship: The specified relationship object will be the primary data of the response

#### Documents for error responses

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
$errorDocument->setJsonApi(new JsonApiObject("1.0"));
$errorDocument->setLinks(ErrorLinks::createWithoutBaseUri()->setAbout("https://example.com/api/errors/404")));
$errorDocument->addError(new MyError());
```

### Resources

Documents for successful responses can contain one or more top-level resources and included resources.
That's why resources are responsible for converting domain objects into JSON:API resources and resource
identifiers.

Although you are encouraged to create one transformer for each resource type, you also have the ability to define
"composite" resources following the Composite design pattern.

Resources must implement the `ResourceInterface`. In order to facilitate this job, you can also extend the
`AbstractResource` class.

Children of the `AbstractResource` class need several abstract methods to be implemented - most of them are similar to
the ones seen in the Document objects. The following example illustrates a resource dealing with a book domain object
and its "authors" and "publisher" relationships.

```php
class BookResource extends AbstractResource
{
    /**
     * @var AuthorResource
     */
    private $authorResource;

    /**
     * @var PublisherResource
     */
    private $publisherResource;

    /**
     * You can type-hint the object property this way.
     * @var array
     */
    protected $object;

    public function __construct(
        AuthorResource $authorResource,
        PublisherResource $publisherResource
    ) {
        $this->authorResource = $authorResource;
        $this->publisherResource = $publisherResource;
    }

    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $book
     */
    public function getType($book): string
    {
        return "book";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $book
     */
    public function getId($book): string
    {
        return $this->object["id"];

        // This is equivalent to the following (the $book parameter is used this time instead of $this->object):
        return $book["id"];
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $book
     */
    public function getMeta($book): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new ResourceLinks object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $book
     */
    public function getLinks($book): ?ResourceLinks
    {
        return new ResourceLinks::createWithoutBaseUri()->setSelf(new Link("/books/" . $this->getId($book)));

        // This is equivalent to the following:
        // return new ResourceLinks("", new Link("/books/" . $this->getResourceId()));
    }

    /**
     * Provides information about the "attributes" member of the current resource.
     *
     * The method returns an array where the keys signify the attribute names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return the value of the corresponding attribute.
     *
     * @param array $book
     * @return callable[]
     */
    public function getAttributes($book): array
    {
        return [
            "title" => function () {
                return $this->object["title"];
            },
            "pages" => function () {
                return (int) $this->object["pages"];
            },
        ];

        // This is equivalent to the following (the $book parameter is used this time instead of $this->object):
        return [
            "title" => function (array $book) {
                return $book["title"];
            },
            "pages" => function (array $book) {
                return (int) $book["pages"];
            },
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $book
     */
    public function getDefaultIncludedRelationships($book): array
    {
        return ["authors"];
    }

    /**
     * Provides information about the "relationships" member of the current resource.
     *
     * The method returns an array where the keys signify the relationship names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return a new relationship instance (to-one or to-many).
     *
     * @param array $book
     * @return callable[]
     */
    public function getRelationships($book): array
    {
        return [
            "authors" => function () {
                return ToManyRelationship::create()
                    ->setLinks(
                        RelationshipLinks::createWithoutBaseUri()->setSelf(new Link("/books/relationships/authors"))
                    )
                    ->setData($this->object["authors"], $this->authorTransformer);
            },
            "publisher" => function () {
                return ToOneRelationship::create()
                    ->setLinks(
                        RelationshipLinks::createWithoutBaseUri()->setSelf(new Link("/books/relationships/publisher"))
                    )
                    ->setData($this->object["publisher"], $this->publisherTransformer);
            },
        ];

        // This is equivalent to the following (the $book parameter is used this time instead of $this->object):

        return [
            "authors" => function (array $book) {
                return ToManyRelationship::create()
                    ->setLinks(
                        RelationshipLinks::createWithoutBaseUri()->setSelf(new Link("/books/relationships/authors"))
                    )
                    ->setData($book["authors"], $this->authorTransformer);
            },
            "publisher" => function ($book) {
                return ToOneRelationship::create()
                    ->setLinks(
                        RelationshipLinks::createWithoutBaseUri()->setSelf(new Link("/books/relationships/publisher"))
                    )
                    ->setData($book["publisher"], $this->publisherTransformer);
            },
        ];
    }
}
```

Generally, you don't use resources directly. Only documents need them to be able to fill the "data", the "included",
and the "relationship" members in the responses.

### Hydrators

Hydrators allow us to initialize the properties of a domain object as required by the current HTTP request. This means,
when a client wants to create or update a resource, hydrators can help instantiate a domain object, which can then be
validated, saved etc.

There are three abstract hydrator classes in Woohoo Labs. Yin:

- `AbstractCreateHydrator`: It can be used for requests which create a new resource
- `AbstractUpdateHydrator`: It can be used for requests which update an existing resource
- `AbstractHydrator`: It can be used for both type of requests

For the sake of brevity, we only introduce the usage of the latter class as it is simply the union of
`AbstractCreateHydrator` and `AbstractUpdateHydrator`. Let's have a look at an example hydrator:

```php
class BookHydrator extends AbstractHydrator
{
    /**
     * Determines which resource types can be accepted by the hydrator.
     *
     * The method should return an array of acceptable resource types. When such a resource is received for hydration
     * which can't be accepted (its type doesn't match the acceptable types of the hydrator), a ResourceTypeUnacceptable
     * exception will be raised.
     *
     * @return string[]
     */
    protected function getAcceptedTypes(): array
    {
        return ["book"];
    }

    /**
     * Validates a client-generated ID.
     *
     * If the $clientGeneratedId is not a valid ID for the domain object, then
     * the appropriate exception should be thrown: if it is not well-formed then
     * a ClientGeneratedIdNotSupported exception can be raised, if the ID already
     * exists then a ClientGeneratedIdAlreadyExists exception can be thrown.
     *
     * @throws ClientGeneratedIdNotSupported
     * @throws ClientGeneratedIdAlreadyExists
     * @throws Exception
     */
    protected function validateClientGeneratedId(
        string $clientGeneratedId,
        JsonApiRequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory
    ) {
        if ($clientGeneratedId !== null) {
            throw $exceptionFactory->createClientGeneratedIdNotSupportedException($request, $clientGeneratedId);
        }
    }

    /**
     * Produces a new ID for the domain objects.
     *
     * UUID-s are preferred according to the JSON:API specification.
     */
    protected function generateId(): string
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
     * @return mixed|void
     */
    protected function setId($book, string $id)
    {
        $book["id"] = $id;

        return $book;
    }

    /**
     * You can validate the request.
     *
     * @throws JsonApiExceptionInterface
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        // WARNING! THIS CONDITION CONTRADICTS TO THE SPEC
        if ($request->getAttribute("title") === null) {
            throw new LogicException("The 'title' attribute is required!");
        }
    }

    /**
     * Provides the attribute hydrators.
     *
     * The method returns an array of attribute hydrators, where a hydrator is a key-value pair:
     * the key is the specific attribute name which comes from the request and the value is a
     * callable which hydrates the given attribute.
     * These callables receive the domain object (which will be hydrated), the value of the
     * currently processed attribute, the "data" part of the request and the name of the attribute
     * to be hydrated as their arguments, and they should mutate the state of the domain object.
     * If it is an immutable object or an array (and passing by reference isn't used),
     * the callable should return the domain object.
     *
     * @param array $book
     * @return callable[]
     */
    protected function getAttributeHydrator($book): array
    {
        return [
            "title" => function (array $book, $attribute, $data, $attributeName) {
                $book["title"] = $attribute;

                return $book;
            },
            "pages" => function (array &$book, $attribute, $data, $attributeName) {
                $book["pages"] = $attribute;
            },
        ];
    }

    /**
     * Provides the relationship hydrators.
     *
     * The method returns an array of relationship hydrators, where a hydrator is a key-value pair:
     * the key is the specific relationship name which comes from the request and the value is a
     * callable which hydrate the previous relationship.
     * These callables receive the domain object (which will be hydrated), an object representing the
     * currently processed relationship (it can be a ToOneRelationship or a ToManyRelationship
     * object), the "data" part of the request and the relationship name as their arguments, and
     * they should mutate the state of the domain object.
     * If it is an immutable object or an array (and passing by reference isn't used),
     * the callable should return the domain object.
     *
     * @param mixed $book
     * @return callable[]
     */
    protected function getRelationshipHydrator($book): array
    {
        return [
            "authors" => function (array $book, ToManyRelationship $authors, $data, string $relationshipName) {
                $book["authors"] = BookRepository::getAuthors($authors->getResourceIdentifierIds());

                return $book;
            },
            "publisher" => function (array &$book, ToOneRelationship $publisher, $data, string $relationshipName) {
                $book["publisher"] = BookRepository::getPublisher($publisher->getResourceIdentifier()->getId());
            },
        ];
    }
}
```

According to the [book example](examples/Book), the following request:

```http
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

### Exceptions

Woohoo Labs. Yin was designed to make error handling as easy and customizable as possible. That's why all the default
exceptions extend the `JsonApiException` class and contain an [error document](#documents-for-error-responses) with the
appropriate error object(s). That's why if you want to respond with an error document in case of an exception you
need to do the following:

```php
try {
    // Do something which results in an exception
} catch (JsonApiExceptionInterface $e) {
    // Get the error document from the exception
    $errorDocument = $e->getErrorDocument();

    // Instantiate the responder - make sure to pass the correct dependencies to it
    $responder = Responder::create($request, $response, $exceptionFactory, $serializer);

    // Create a response from the error document
    $responder->genericError($errorDocument);

    // Emit the HTTP response
    sendResponse($response);
}
```

To guarantee total customizability, we introduced the concept of __Exception Factories__. These are classes
which create all the exceptions thrown by Woohoo Labs. Yin. As an Exception Factory of __your own choice__ is passed to
every transformer and hydrator, you can completely customize what kind of exceptions are thrown.

The default [Exception Factory](https://github.com/woohoolabs/yin/blob/master/src/JsonApi/Exception/DefaultExceptionFactory)
creates children of [`JsonApiException`](src/JsonApi/Exception)s but you are free to create any `JsonApiExceptionInterface`
exceptions. If you only want to customize the error document or the error objects of your exceptions, just extend the
basic `Exception` class and create your `createErrorDocument()` or `getErrors()` methods.

### `JsonApi` class

The `JsonApi` class is the orchestrator of the whole framework. It is highly recommended to utilize this class
if you want to use the entire functionality of Woohoo Labs. Yin. You can find various examples about the usage
of it in the [examples section](#examples) or [example directory](https://github.com/woohoolabs/yin/blob/master/examples).

### `JsonApiRequest` class

The `JsonApiRequest` class implements the `WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface` which extends the PSR-7
`ServerRequestInterface` with some useful, JSON:API related methods. For further information about the available methods,
please refer to the documentation of [`JsonApiRequestInterface`](https://github.com/woohoolabs/yin/blob/master/src/JsonApi/Request/JsonApiRequestInterface.php).

## Advanced Usage

This section guides you through the advanced features of Yin.

### Pagination

Yin is able to help you paginate your collection of resources. First, it provides some shortcuts for querying the
request query parameters when page-based, offset-based, or cursor-based pagination strategies are used.

#### Page-based pagination

Yin looks for the `page[number]` and the `page[size]` query parameters and parses their value. If any of them is missing
then the default page number or size will be used ("1" and "10" in the following example).

```php
$pagination = $jsonApi->getPaginationFactory()->createPageBasedPagination(1, 10);
```

#### Fixed page-based pagination

Yin looks for the `page[number]` query parameter and parses its value. If it is missing then the default page number
will be used ("1" in the following example). This strategy can be useful if you do not want to expose the page size
at all.

```php
$pagination = $jsonApi->getPaginationFactory()->createFixedPageBasedPagination(1);
```

#### Offset-based pagination

Yin looks for the `page[offset]` and the `page[limit]` query parameters and parses their value. If any of them is missing
then the default offset or limit will be used ("1" and "10" in the following example).

```php
$pagination = $jsonApi->getPaginationFactory()->createOffsetBasedPagination(1, 10);
```

#### Cursor-based pagination

Yin looks for the `page[cursor]` and the `page[size]` query parameters and parses their value. If any of them is missing
then the default cursor or size will be used ("2016-10-01" or 10 in the following example).

```php
$pagination = $jsonApi->getPaginationFactory()->createCursorBasedPagination("2016-10-01", 10);
```

#### Fixed cursor-based pagination

Yin looks for the `page[cursor]` query parameter and parses its value. If it is missing then the default cursor will
be used ("2016-10-01" in the following example).

```php
$pagination = $jsonApi->getPaginationFactory()->createFixedCursorBasedPagination("2016-10-01");
```

#### Custom pagination

If you need a custom pagination strategy, you may use the `JsonApiRequestInterface::getPagination()` method which returns an
array of pagination parameters.

```php
$paginationParams = $jsonApi->getRequest()->getPagination();

$pagination = new CustomPagination($paginationParams["from"] ?? 1, $paginationParams["to"] ?? 1);
```

#### Usage

As soon as you have the appropriate pagination object, you may use them when you fetch your data from a data source:

```
$users = UserRepository::getUsers($pagination->getPage(), $pagination->getSize());
```

#### Pagination links

The JSON:API spec makes it available to provide pagination links for your resource collections. Yin is able to help you
in this regard too. You have use the `DocumentLinks::setPagination()` method when you define links for your documents.
It expects the paginated URI and an object implementing the `PaginationLinkProviderInterface` as seen in the following
example:

```php
public function getLinks(): ?DocumentLinks
{
    return DocumentLinks::createWithoutBaseUri()->setPagination("/users", $this->object);
}
```

To make things even easier, there are some `LinkProvider` traits in order to ease the development of
`PaginationLinkProviderInterface` implementations of the built-in pagination strategies. For example a collection
for the `User` objects can use the `PageBasedPaginationLinkProviderTrait`. This way, only three abstract methods has
to be implemented:

```php
class UserCollection implements PaginationLinkProviderInterface
{
    use PageBasedPaginationLinkProviderTrait;

    public function getTotalItems(): int
    {
        // ...
    }

    public function getPage(): int
    {
        // ...
    }

    public function getSize(): int
    {
        // ...
    }

    // ...
}
```

You can find the full example [here](https://github.com/woohoolabs/yin/blob/master/examples/Utils/Collection.php).

### Loading relationship data efficiently

Sometimes it can be beneficial or necessary to fine-tune data retrieval of relationshipS. A possible scenario might be
when you have a "to-many" relationship containing gazillion items. If this relationship isn't always needed than you
might only want to return a data key of a relationship when the relationship itself is included in the response. This
optimization can save you bandwidth by omitting resource linkage.

An example is extracted from the [`UserResource`](https://github.com/woohoolabs/yin/blob/master/examples/User/JsonApi/Resource/UserResource.php)
example class:

```php
public function getRelationships($user): array
{
    return [
        "contacts" => function (array $user) {
            return
                ToManyRelationship::create()
                    ->setData($user["contacts"], $this->contactTransformer)
                    ->omitDataWhenNotIncluded();
        },
    ];
}
```

By using the `omitDataWhenNotIncluded()` method, the relationship data will be omitted when the relationship is not
included. However, sometimes this optimization is not enough on its own. Even though we can save bandwidth with the prior
technique, the relationship still has to be loaded from the data source (probably from a database), because we pass it
to the relationship object with the `setData()` method.

This problem can be mitigated by lazy-loading the relationship. To do so, you have to use `setDataAsCallable()` method
instead of `setData()`:

```php
public function getRelationships($user): array
{
    return [
        "contacts" => function (array $user) {
            return
                ToManyRelationship::create()
                    ->setDataAsCallable(
                        function () use ($user) {
                            // Lazily load contacts from the data source
                            return $user->loadContactsFromDataSource();
                        },
                        $this->contactTransformer
                    )
                    ->omitDataWhenNotIncluded()
                ;
        },
    ];
}
```

This way, the contacts of a user will only be loaded when the given relationship's `data` key is present in the response,
allowing your API to be as efficient as possible.

### Injecting metadata into documents

Metadata can be injected into documents on-the-fly. This comes handy if you want to customize or decorate your
responses. For example if you would like to inject a cache ID into the response document, you could use the following:

```php
// Calculate the cache ID
$cacheId = calculateCacheId();

// Respond with "200 Ok" status code along with the book document containing the cache ID in the meta data
return $jsonApi->respond()->ok($document, $book, ["cache_id" => $cacheId]);
```

Usually, the last argument of each responder method can be used to add meta data to your documents.

### Content negotiation

The JSON:API standard specifies [some rules](#content-negotiation-servers) about content
negotiation. Woohoo Labs. Yin tries to help you enforce them with the `RequestValidator` class. Let's first create
a request validator to see it in action:

```php
$requestValidator = new RequestValidator(new DefaultExceptionFactory(), $includeOriginalMessageInResponse);
```

In order to customize the exceptions which can be thrown, it is necessary to provide an [Exception Factory](#exceptions).
On the other hand, the `$includeOriginalMessageInResponse` argument can be useful in a development environment
when you also want to return the original request body that triggered the exception in the error response.

In order to validate whether the current request's `Accept` and `Content-Type` headers conform to the JSON:API
specification, use this method:

```php
$requestValidator->negotiate($request);
```

### Request/response validation

You can use the following method to check if the query parameters of the current request are in line with
[the naming rules](https://jsonapi.org/format/#query-parameters):

```php
$requestValidator->validateQueryParams($request);
```

> Note: In order to apply the following validations, remember to install the
> [optional dependencies](#install) of Yin.

Furthermore, the request body can be validated if it is a well-formed JSON document:

```php
$requestValidator->validateJsonBody($request);
```

Similarly, responses can be validated too. Let's create a response validator first:

```php
$responseValidator = new ResponseValidator(
    new JsonSerializer(),
    new DefaultExceptionFactory(),
    $includeOriginalMessageInResponse
);
```

To ensure that the response body is a well-formed JSON document, one can use the following method:

```php
$responseValidator->validateJsonBody($response);
```

To ensure that the response body is a well-formed JSON:API document, one can use the following method:

```php
$responseValidator->validateJsonApiBody($response);
```

Validating the responses can be useful in a development environment to find possible bugs early.

### Custom serialization

You can configure Yin to serialize responses in a custom way instead of using the default serializer (`JsonSerializer`)
that utilizes the `json_encode()` function to write JSON:API documents into the response body.

In the majority of the use-cases, the default serializer should be sufficient for your needs, but sometimes you might
need more sophistication. Or sometimes you want to do nasty things like returning your JSON:API response as an array
without any serialization in case your API endpoint was called "internally".

In order to use a custom serializer, create a class implementing `SerializerInterface` and setup your `JsonApi`
instance accordingly (pay attention to the last argument):

```php
$jsonApi = new JsonApi(new JsonApiRequest(), new Response(), new DefaultExceptionFactory(), new CustomSerializer());
```

### Custom deserialization

You can configure Yin to deserialize requests in a custom way instead of using the default deserializer
(`JsonDeserializer`) that utilizes the `json_decode()` function to parse the contents of the request body.

In the majority of the use-cases, the default deserializer should be sufficient for your needs, but sometimes you might
need more sophistication. Or sometimes you want to do nasty things like calling your JSON:API endpoints "internally"
without converting your request body to JSON format.

In order to use a custom deserializer, create a class implementing `DeserializerInterface` and setup your `JsonApiRequest`
instance accordingly (pay attention to the last argument):

```php
$request = new JsonApiRequest(ServerRequestFactory::fromGlobals(), new DefaultExceptionFactory(), new CustomDeserializer());
```

### Middleware

If you use a middleware-oriented framework (like [Woohoo Labs. Harmony](https://github.com/woohoolabs/harmony),
[Zend-Stratigility](https://github.com/zendframework/zend-stratigility/),
[Zend-Expressive](https://github.com/zendframework/zend-expressive/) or
[Slim Framework 3](https://www.slimframework.com/)), you will find the
[Yin-middleware](https://github.com/woohoolabs/yin-middleware) library quite useful. Read the documentation to
learn about its advantages!

## Examples

### Fetching a single resource

```php
public function getBook(JsonApi $jsonApi): ResponseInterface
{
    // Getting the "id" of the currently requested book
    $id = $jsonApi->getRequest()->getAttribute("id");

    // Retrieving a book domain object with an ID of $id
    $book = BookRepository::getBook($id);

    // Instantiating a book document
    $document = new BookDocument(
        new BookResource(
            new AuthorResource(),
            new PublisherResource()
        )
    );

    // Responding with "200 Ok" status code along with the book document
    return $jsonApi->respond()->ok($document, $book);
}
```

### Fetching a collection of resources

```php
public function getUsers(JsonApi $jsonApi): ResponseInterface
{
    // Extracting pagination information from the request, page = 1, size = 10 if it is missing
    $pagination = $jsonApi->getPaginationFactory()->createPageBasedPagination(1, 10);

    // Fetching a paginated collection of user domain objects
    $users = UserRepository::getUsers($pagination->getPage(), $pagination->getSize());

    // Instantiating a users document
    $document = new UsersDocument(new UserResource(new ContactResource()));

    // Responding with "200 Ok" status code along with the users document
    return $jsonApi->respond()->ok($document, $users);
}
```

### Fetching a relationship

```php
public function getBookRelationships(JsonApi $jsonApi): ResponseInterface
{
    // Getting the "id" of the currently requested book
    $id = $jsonApi->getRequest()->getAttribute("id");

    // Getting the currently requested relationship's name
    $relationshipName = $jsonApi->getRequest()->getAttribute("rel");

    // Retrieving a book domain object with an ID of $id
    $book = BookRepository::getBook($id);

    // Instantiating a book document
    $document = new BookDocument(
        new BookResource(
            new AuthorResource(),
            new PublisherResource(
                new RepresentativeResource()
            )
        )
    );

    // Responding with "200 Ok" status code along with the requested relationship document
    return $jsonApi->respond()->okWithRelationship($relationshipName, $document, $book);
}
```

### Creating a new resource

```php
public function createBook(JsonApi $jsonApi): ResponseInterface
{
    // Hydrating a new book domain object from the request
    $book = $jsonApi->hydrate(new BookHydrator(), []);

    // Saving the newly created book
    // ...

    // Creating the book document to be sent as the response
    $document = new BookDocument(
        new BookResource(
            new AuthorResource(),
            new PublisherResource(
                new RepresentativeResource()
            )
        )
    );

    // Responding with "201 Created" status code along with the book document
    return $jsonApi->respond()->created($document, $book);
}
```

### Updating a resource

```php
public function updateBook(JsonApi $jsonApi): ResponseInterface
{
    // Retrieving a book domain object with an ID of $id
    $id = $jsonApi->getRequest()->getResourceId();
    $book = BookRepository::getBook($id);

    // Hydrating the retrieved book domain object from the request
    $book = $jsonApi->hydrate(new BookHydrator(), $book);

    // Updating the book
    // ...

    // Instantiating the book document
    $document = new BookDocument(
        new BookResource(
            new AuthorResource(),
            new PublisherResource(
                new RepresentativeResource()
            )
        )
    );

    // Responding with "200 Ok" status code along with the book document
    return $jsonApi->respond()->ok($document, $book);
}
```

### Updating a relationship of a resource

```php
public function updateBookRelationship(JsonApi $jsonApi): ResponseInterface
{
    // Checking the name of the currently requested relationship
    $relationshipName = $jsonApi->getRequest()->getAttribute("rel");

    // Retrieving a book domain object with an ID of $id
    $id = $jsonApi->getRequest()->getAttribute("id");
    $book = BookRepository::getBook($id);
    if ($book === null) {
        die("A book with an ID of '$id' can't be found!");
    }

    // Hydrating the retrieved book domain object from the request
    $book = $jsonApi->hydrateRelationship($relationshipName, new BookHydrator(), $book);

    // Instantiating a book document
    $document = new BookDocument(
        new BookResource(
            new AuthorResource(),
            new PublisherResource(
                new RepresentativeResource()
            )
        )
    );

    // Responding with "200 Ok" status code along with the book document
    return $jsonApi->respond()->ok($document, $book);
}
```

### How to try it out

If you want to see how Yin works, have a look at the [examples](https://github.com/woohoolabs/yin/tree/master/examples).
If `docker-compose` and `make` is available on your system, then just run the following commands in order to try out the
example API:

```bash
cp .env.dist .env      # You can now edit the settings in the .env file
make composer-install  # Install the Composer dependencies
make up                # Start the webserver
```

And finally, just visit the following URL: `localhost:8080`. You can even restrict the retrieved fields and relationships
via the `fields` and `include` parameters as specified by JSON:API.

Example URIs for the book examples:
- `GET /books/1`: Fetch a book
- `GET /books/1/relationships/authors`: Fetch the authors relationship
- `GET /books/1/relationships/publisher`: Fetch the publisher relationship
- `GET /books/1/authors`: Fetch the authors of a book
- `POST /books`: Create a new book
- `PATCH /books/1`: Update a book
- `PATCH /books/1/relationships/author`: Update the authors of the book
- `PATCH /books/1/relationships/publisher`: Update the publisher of the book

Example URIs for the user examples:
- `GET /users`: Fetch users
- `GET /users/1`: Fetch a user
- `GET /users/1/relationships/contacts`: Fetch the contacts relationship

When you finished your work, simply stop the webserver:

```bash
make down
```

If the prerequisites are not available for you, you have to set up a webserver, and install PHP on your host system as
well as the dependencies via `Composer`.

## Integrations

- [dimvic/yii-yin](https://github.com/dimvic/yii-yin): Integration for Yii 1.1
- [paknahad/jsonapi-bundle](https://github.com/paknahad/jsonapi-bundle): Integration for Symfony
- [qpautrat/woohoolabs-yin-bundle](https://github.com/qpautrat/woohoolabs-yin-bundle): Integration for Symfony

## Versioning

This library follows [SemVer v2.0.0](https://semver.org/).

## Change Log

Please see [CHANGELOG](CHANGELOG.md) for more information on recent changes.

## Testing

Woohoo Labs. Yin has a PHPUnit test suite. To run the tests, run the following command from the project folder:

``` bash
$ phpunit
```

Additionally, you may run `docker-compose up` or `make test` in order to execute the tests.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Support

Please see [SUPPORT](SUPPORT.md) for details.

## Credits

- [Máté Kocsis][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see the [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/woohoolabs/yin.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-build]: https://img.shields.io/github/workflow/status/woohoolabs/yin/Continuous%20Integration
[ico-coverage]: https://img.shields.io/codecov/c/github/woohoolabs/yin
[ico-code-quality]: https://img.shields.io/scrutinizer/g/woohoolabs/yin.svg
[ico-downloads]: https://img.shields.io/packagist/dt/woohoolabs/yin.svg
[ico-support]: https://badges.gitter.im/woohoolabs/yin.svg

[link-version]: https://packagist.org/packages/woohoolabs/yin
[link-build]: https://github.com/woohoolabs/yin/actions
[link-coverage]: https://codecov.io/gh/woohoolabs/yin
[link-code-quality]: https://scrutinizer-ci.com/g/woohoolabs/yin
[link-support]: https://gitter.im/woohoolabs/yin?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge
[link-downloads]: https://packagist.org/packages/woohoolabs/yin
[link-author]: https://github.com/kocsismate
[link-contributors]: ../../contributors
