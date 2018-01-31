# Woohoo Labs. Yin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
[![Gitter][ico-gitter]][link-gitter]

**Woohoo Labs. Yin is a PHP framework which helps you to build beautifully crafted JSON:APIs.**

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
    * [Request class](#request-class)
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

[JSON:API](http://jsonapi.org) specification
[reached 1.0 on 29th May 2015](http://www.programmableweb.com/news/new-json-api-specification-aims-to-speed-api-development/2015/06/10)
and we also believe it is a big day for RESTful APIs as this specification can help you make APIs more robust and
future-proof. Woohoo Labs. Yin (named after Yin-Yang) was born to bring efficiency and elegance to your JSON:API
servers, while [Woohoo Labs. Yang](https://github.com/woohoolabs/yang) is its client-side counterpart.

### Features

- 100% [PSR-7](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md) compatibility
- 99% [JSON:API 1.0](http://jsonapi.org/) compatibility (approximately)
- Developed for efficiency and ease of use
- Extensive documentation and examples
- Provides Documents and Transformers to fetch resources
- Provides Hydrators to create and update resources
- [Additional middleware](https://github.com/woohoolabs/yin-middleware) for the easier kickstart and debugging

### Why Yin?

#### Complete JSON:API framework

Woohoo Labs. Yin is a framework-agnostic library which supports the vast majority of the JSON:API specification:
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

The only thing you need before getting started is [Composer](http://getcomposer.org).

### Install a PSR-7 implementation:

Because Yin requires a PSR-7 implementation (a package which provides the `psr/http-message-implementation` virtual
package), you must install one first. You may use [Zend Diactoros](https://github.com/zendframework/zend-diactoros) or
any other library of your preference:

```bash
$ composer require zendframework/zend-diactoros:^1.3.0
```

### Install Yin:

To install the latest version of this library, run the command below:

```bash
$ composer require woohoolabs/yin
```

Yin requires PHP 7.1 at least but you can use Yin 2.0.5 for PHP 7.0.

### Install the optional dependencies:

If you want to take advantage of request/response validation then you have to also ask for the following
dependencies:

```bash
$ composer require justinrainbow/json-schema:^2.0.0
$ composer require seld/jsonlint:^1.4.0
```

## Basic Usage

When using Woohoo Labs. Yin, you will create:
- Documents and resource transformers in order to map domain objects to JSON:API responses
- Hydrators in order to transform resources in a POST or PATCH request to domain objects

Furthermore, a [`JsonApi`](#jsonapi-class) class will be responsible for the instrumentation, while a PSR-7 compatible
[`Request`](#request-class) class provides functionalities you commonly need.

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
the first one doesn't need a [resource transformer](#resource-transformers). For this reason, it is preferable to use
the former for really simple domain objects (like messages), while the latter works better for more complex domain
objects (like users or addresses).

`AbstractSingleResourceDocument` and `AbstractCollectionDocument` both need a
[resource transformer](#resource-transformers) to work, which is a concept introduced in the following sections.
For now, it is enough to know that one must be passed for the documents during instantiation. This means that a
minimal constructor of your documents should look like this:

```php
public function __construct(MyResourceTransformer $transformer)
{
    parent::__construct($transformer);
}
```

You can of course provide other dependencies for your constructor or completely omit it if you don't need it.

When you extend either `AbstractSingleResourceDocument` or `AbstractCollectionDocument`, they both require
you to implement the following methods:

```php
/**
 * Provides information about the "jsonapi" member of the current document.
 *
 * The method returns a new JsonApiObject schema object if this member should be present or null
 * if it should be omitted from the response.
 *
 * @return JsonApiObject|null
 */
public function getJsonApi()
{
    return new JsonApiObject("1.0");
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
            "offset" => $this->domainObject->getOffset(),
            "limit" => $this->domainObject->getLimit(),
            "total" => $this->domainObject->getCount()
        ]
    ];
}
```

Documents may also have a "meta" member which can contain any non-standard information. The example above adds
information about pagination to the document.

Note that the `domainObject` property is a variable of any type (in this case it is a hypothetical collection),
and this is the main "subject" of the document.

```php
/**
 * Provides information about the "links" member of the current document.
 *
 * The method returns a new Links schema object if you want to provide linkage data
 * for the document or null if the member should be omitted from the response.
 */
public function getLinks(): ?Links
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
            "self" => new Link("/books/" . $this->getResourceId())
        ]
    );
}
```

This time, we want a self link to appear in the document. For this purpose, we utilize the `getResourceId()` method,
which is a shortcut of calling the resource transformer (which is introduced below) to obtain the ID of the
primary resource (`$this->transformer->getId($this->domainObject)`).

The only difference between the `AbstractSingleResourceDocument` and `AbstractCollectionDocument` is the way they
regard the `domainObject`. The first one regards it as a single domain object while the latter regards it
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
$errorDocument->setLinks(Links::createWithoutBaseUri()->setSelf("http://example.com/api/errors/404")));
$errorDocument->addError(new MyError());
```

### Resource transformers

Documents for successful responses can contain one or more top-level resources and included resources.
That's why resource transformers are responsible for converting domain objects into JSON:API resources and resource
identifiers.

Although you are encouraged to create one transformer for each resource type, you also have the ability to define
"composite" resource transformers following the Composite design pattern.

Resource transformers must implement the `ResourceTransformerInterface`. In order to facilitate this job, you can also
extend the `AbstractResourceTransformer` class.

Children of the `AbstractResourceTransformer` class need several abstract methods to be implemented - most of which
are the same as seen in the Document objects. The following example illustrates a resource transformer dealing with
a book domain object and its "authors" and "publisher" relationships.

```php
class BookResourceTransformer extends AbstractResourceTransformer
{
    /**
     * @var AuthorResourceTransformer
     */
    private $authorTransformer;

    /**
     * @var PublisherResourceTransformer
     */
    private $publisherTransformer;

    public function __construct(
        AuthorResourceTransformer $authorTransformer,
        PublisherResourceTransformer $publisherTransformer
    ) {
        $this->authorTransformer = $authorTransformer;
        $this->publisherTransformer = $publisherTransformer;
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
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $book
     */
    public function getLinks($book): ?Links
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
            "title" => function (array $book) {
                return $book["title"];
            },
            "pages" => function (array $book) {
                return $this->toInt($book["pages"]);
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
            "authors" => function (array $book) {
                return ToManyRelationship::create()
                    ->setLinks(
                        Links::createWithoutBaseUri()->setSelf(new Link("/books/relationships/authors"))
                    )
                    ->setData($book["authors"], $this->authorTransformer)
                ;
            },
            "publisher" => function ($book) {
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
the "included" and the "relationship" members in the responses.

### Hydrators

Hydrators allow us to initialize the properties of a domain object as required by the current HTTP request. This means,
when a client wants to create or update a resource, hydrators can help instantiate a domain object, which can then be
validated, saved etc.

There are three abstract hydrator classes in Woohoo Labs. Yin:

- `AbstractCreateHydrator`: It can be used for requests to create a new resource
- `AbstractUpdateHydrator`: It can be used for requests to update an existing resource
- `AbstractHydrator`: It can be used for both type of requests

For the sake of brevity, we only introduce the usage of the latter class as it is simply the union of
`AbstractCreateHydrator` and `AbstractUpdateHydrator`. Let's have a look at an example hydrator:

```php
class BookHydator extends AbstractHydrator
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
        RequestInterface $request,
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
    protected function validateRequest(RequestInterface $request): void
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
            }
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
     * @param mixed $domainObject
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
            }
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
appropriate error object(s). That's why if you want to respond with an error document in case of an exception you only
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

### `Request` class

The `Request` class implements the `WoohooLabs\Yin\JsonApi\Request\RequestInterface` which extends the PSR-7
`ServerRequestInterface` with some useful, JSON:API related methods. For further information about the available methods,
please refer to the documentation of [`RequestInterface`](https://github.com/woohoolabs/yin/blob/master/src/JsonApi/Request/RequestInterface.php).

## Advanced Usage

This section guides you through the advanced features of Yin.

### Pagination

Yin is able to help you paginate your collection of resources. First, it provides some shortcuts for querying the
request query parameters when page-based, offset-based, or cursor-based pagination strategies are used.

#### Page-based pagination

Yin looks for the `page[number]` and the `page[size]` query parameters and parses their value. If any of them is missing
then the default page number and size will be used ("1" and "10" in the following example).

```php
$pagination = $jsonApi->getRequest()->getPageBasedPagination(1, 10);
```

#### Fixed page-based pagination

Yin looks for the `page[number]` query parameter and parses its value. If it is missing then the default page number
will be used ("1" in the following example). This strategy can be useful if you do not want to expose the page size
at all.

```php
$pagination = $jsonApi->getRequest()->getFixedPageBasedPagination(1);
```

#### Offset-based pagination

Yin looks for the `page[offset]` and the `page[limit]` query parameters and parses their value. If any of them is missing
then the default offset and limit will be used ("1" and "10" in the following example).

```php
$pagination = $jsonApi->getRequest()->getOffsetBasedPagination(1, 10);
```

#### Cursor-based pagination

Yin looks for the `page[cursor]` query parameter and parses its value. If it is missing then the default cursor will
be used ("2016-10-01" in the following example).

```php
$pagination = $jsonApi->getRequest()->getCursorBasedPagination("2016-10-01");
```

#### Custom pagination

If you need a custom pagination strategy, you may use the `getPagination()` method which returns an array of pagination
parameters.

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
in this regard too. You only have use the `Links::setPagination()` method when you define links for your documents or
resources. It expects the paginated URI and an object implementing the `PaginationLinkProviderInterface` as
seen in the following example:

```php
public function getLinks()
{
    return Links::createWithoutBaseUri()->setPagination("/users", $this->domainObject);
}
```

To make things even easier, there are some `LinkProvider` traits in order to ease the development of
`PaginationLinkProviderInterface` implementations of the built-in pagination strategies. For example a collection
for the `User` objects can use the `PageBasedPaginationLinkProviderTrait`. This way, you only have to implement three
really simple abstract methods:

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

You can find the full example [here](https://github.com/woohoolabs/yin/blob/master/utils/Collection.php).

### Loading relationship data efficiently

Sometimes it can be beneficial or necessary to fine-tune data retrieval of relationshipS. A possible scenario might be
when you have a "to-many" relationship containing gazillion items. If this relationship isn't always needed than you
might only want to return a data key of a relationship when the relationship itself is included in the response. This
optimization can save you bandwidth by omitting resource linkage.

An example is extracted from the [`UserResourceTransformer`](https://github.com/woohoolabs/yin/blob/master/examples/User/JsonApi/Resource/UserResourceTransformer.php)
example class:

```php
public function getRelationships($user): array
{
    return [
        "contacts" => function (array $user) {
            return
                ToManyRelationship::create()
                    ->setData($user["contacts"], $this->contactTransformer)
                    ->omitWhenNotIncluded()
                ;
        }
    ];
}
```

With usage of the `omitWhenNotIncluded()` method, the relationship data will be omitted when the relationship is not
included. However, sometimes this optimization is not enough on its own. Even though we can save bandwidth with the prior
technique, the relationship still has to be loaded from the data source (probably from a database), because we pass it
to the relationship object with the `setData()` method.

This problem can be mitigated by lazy-loading the relationship. To do so, you only have to change `setData()`
with the `setDataAsCallable()` method:

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
                    ->omitWhenNotIncluded()
                ;
        }
    ];
}
```

This way, the contacts of a user will only be loaded when the given relationship's `data` key is present in the response,
allowing your API to be as efficient as possible.

### Injecting metadata into documents

Metadata can be injected into documents on-the-fly. This comes handy if you want to customize or decorate your
responses (e.g.: providing a cache ID to the returned document).

The easiest way to check this functionality is to have a look at the [first examples](#fetching-a-single-resource),
which responds with a book document:

```php
return $jsonApi->respond()->ok($document, $book);
```

If you would like to inject a cache ID into it, you could write this instead:

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

To customize the exceptions which can be thrown, it is necessary to provide an [Exception Factory](#exceptions).
On the other hand, the `$includeOriginalMessageInResponse` argument can be useful in a development environment
when you also want to return the original message in the error response which may be triggered by the exception.

In order to validate whether the current request's `Accept` and `Content-Type` headers conform to the JSON:API
specification, use this method:

```php
$requestValidator->negotiate($request);
```

### Request/response validation

You can use the following method to check if the query parameters of the current request are in line with
[the naming rules](http://jsonapi.org/format/#query-parameters):

```php
$requestValidator->validateQueryParams($request);
```

> Note: In order to apply the following validations, remember to install the
> [optional dependencies](#install) of Yin.

Furthermore, the request body can be validated if it is a well-formed JSON document:

```php
$requestValidator->lintBody($request);
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
$responseValidator->lintBody($response);
```

To ensure that the response body is a well-formed JSON:API document, one can use the following method:

```php
$responseValidator->validateBody($response);
```

Validating the responses can be useful in a development environment to find possible bugs early.

### Custom serialization

You can configure Yin to serialize responses in a custom way instead of using default serializer (`JsonSerializer`)
which utilizes the `json_encode()` function to write JSON:API documents to the response body.

In the majority of the use-cases, the default serializer should be sufficient for your needs, but sometimes you might
need more sophistication. Or sometimes you want to do nasty things like returning your JSON:API response as an array
without any serialization in case your API endpoint was called "internally".

In order to use a custom serializer, create a class implementing `SerializerInterface` and setup your `JsonApi`
instance accordingly (pay attention to the last argument):

```php
$jsonApi = new JsonApi(new Request(), new Response(), new ExceptionFactory(), new CustomSerializer());
```

### Custom deserialization

You can configure Yin to deserialize requests in a custom way instead of using the default deserializer
(`JsonDeserializer`) which utilizes the `json_decode()` function to parse the contents of the request body.

In the majority of the use-cases, the default deserializer should be sufficient for your needs, but sometimes you might
need more sophistication. Or sometimes you want to do nasty things like calling your JSON:API endpoints "internally"
without converting your request body to JSON format.

In order to use a custom deserializer, create a class implementing `DeserializerInterface` and setup your `Request`
instance accordingly (pay attention to the last argument):

```php
$request = new Request(ServerRequestFactory::fromGlobals(), new ExceptionFactory(), new CustomDeserializer());
```

### Middleware

If you use a middleware-oriented framework (like [Woohoo Labs. Harmony](https://github.com/woohoolabs/harmony),
[Zend-Stratigility](https://github.com/zendframework/zend-stratigility/),
[Zend-Expressive](https://github.com/zendframework/zend-expressive/) or
[Slim Framework 3](http://www.slimframework.com/)), you will find the
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
        new BookResourceTransformer(
            new AuthorResourceTransformer(),
            new PublisherResourceTransformer()
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
    $pagination = $jsonApi->getRequest()->getPageBasedPagination(1, 10);

    // Fetching a paginated collection of user domain objects
    $users = UserRepository::getUsers($pagination->getPage(), $pagination->getSize());

    // Instantiating a users document
    $document = new UsersDocument(new UserResourceTransformer(new ContactResourceTransformer()));

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
        new BookResourceTransformer(
            new AuthorResourceTransformer(),
            new PublisherResourceTransformer(
                new RepresentativeResourceTransformer()
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
    $book = $jsonApi->hydrate(new BookHydator(), []);

    // Saving the newly created book
    // ...

    // Creating the book document to be sent as the response
    $document = new BookDocument(
        new BookResourceTransformer(
            new AuthorResourceTransformer(),
            new PublisherResourceTransformer(
                new RepresentativeResourceTransformer()
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
    $book = $jsonApi->hydrate(new BookHydator(), $book);
    
    // Updating the book
    // ...

    // Instantiating the book document
    $document = new BookDocument(
        new BookResourceTransformer(
            new AuthorResourceTransformer(),
            new PublisherResourceTransformer(
                new RepresentativeResourceTransformer()
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
    $book = $jsonApi->hydrateRelationship($relationshipName, new BookHydator(), $book);

    // Instantiating a book document
    $document = new BookDocument(
        new BookResourceTransformer(
            new AuthorResourceTransformer(),
            new PublisherResourceTransformer(
                new RepresentativeResourceTransformer()
            )
        )
    );

    // Responding with "200 Ok" status code along with the book document
    return $jsonApi->respond()->ok($document, $book);
}
```

### How to try it out
If you want to know more about how Yin works, have a look at the
[examples](https://github.com/woohoolabs/yin/tree/master/examples). Set up a web server, run `composer install` in
Yin's root directory and visit the URLs listed below. You can restrict the retrieved fields and relationships with
the `fields` and `include` parameters as specified by JSON:API.

Example URLs for the book resources:
- `GET examples/?path=/books/1`: Fetch a book
- `GET examples/?path=/books/1/relationships/authors`: Fetch the authors relationship
- `GET examples/?path=/books/1/relationships/publisher`: Fetch the publisher relationship
- `GET examples/?path=/books/1/authors`: Fetch the authors of a book
- `POST examples/?path=/books`: Create a new book
- `PATCH examples/?path=/books/1`: Update a book
- `PATCH examples/?path=/books/1/relationships/author`: Update the authors of the book
- `PATCH examples/?path=/books/1/relationships/publisher`: Update the publisher of the book

Example URLs for the user resources:
- `GET examples/?path=/users`: Fetch users
- `GET examples/?path=/users/1`: Fetch a user
- `GET examples/?path=/users/1/relationships/contacts`: Fetch the contacts relationship

## Integrations

- [dimvic/yii-yin](https://github.com/dimvic/yii-yin): integration for Yii 1.1
- [qpautrat/woohoolabs-yin-bundle](https://github.com/qpautrat/woohoolabs-yin-bundle): integration for Symfony

## Versioning

This library follows [SemVer v2.0.0](http://semver.org/).

## Change Log

Please see [CHANGELOG](CHANGELOG.md) for more information on recent changes.

## Testing

Woohoo Labs. Yin has a PHPUnit test suite. To run the tests, run the following command from the project folder
after you have copied phpunit.xml.dist to phpunit.xml:

``` bash
$ phpunit
```

Additionally, you may run `docker-compose up` in order to execute the tests.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Support

Please see [SUPPORT](SUPPORT.md) for details.

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
[ico-gitter]: https://badges.gitter.im/woohoolabs/yin.svg

[link-packagist]: https://packagist.org/packages/woohoolabs/yin
[link-travis]: https://travis-ci.org/woohoolabs/yin
[link-scrutinizer]: https://scrutinizer-ci.com/g/woohoolabs/yin/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/woohoolabs/yin
[link-gitter]: https://gitter.im/woohoolabs/yin?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge
[link-downloads]: https://packagist.org/packages/woohoolabs/yin
[link-author]: https://github.com/kocsismate
[link-contributors]: ../../contributors
