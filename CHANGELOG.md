## 5.0.0 - unreleased

ADDED:

CHANGED:

REMOVED:

FIXED:

## 4.3.0 - 2021-04-20

ADDED:

- [#104](https://github.com/woohoolabs/yin/issues/104): Support for validating the hydrated domain object

FIXED:

- [#103](https://github.com/woohoolabs/yin/issues/103): Missing data in response if using omitDataWhenNotIncluded

## 4.2.1 - 2021-01-28

FIXED:

- [#101](https://github.com/woohoolabs/yin/issues/101): Parsed body always contains an empty array when using Symfony requests

## 4.2.0 - 2021-01-23

ADDED:

- [#98](https://github.com/woohoolabs/yin/issues/98): Support for validating top-level members in requests
- [#96](https://github.com/woohoolabs/yin/issues/96): Support for providing a custom schema path to `ResponseValidator`

FIXED:

- [#100](https://github.com/woohoolabs/yin/issues/100): Error in createResourceIdInvalidException
- [#97](https://github.com/woohoolabs/yin/issues/97): Incorrect encoding of (pagination) query parameters

## 4.1.2 - 2020-06-23

ADDED:

- Support for PHP 8
- Support for PHPUnit 9

CHANGED:

- Instead of zendframework packages, laminas are required

## 4.1.1 - 2019-12-30

FIXED:

- [#93](https://github.com/woohoolabs/yin/issues/93): Using validateJsonBody() before getResource() makes request body empty

## 4.1.0 - 2019-11-04

ADDED:

- [#90](https://github.com/woohoolabs/yin/pull/90): Support for adding custom links to Resources, Relationships, and Errors

CHANGED:

- Updated JSON:API schema to the latest version

## 4.0.1 - 2019-06-05

FIXED:

- [#83](https://github.com/woohoolabs/yin/issues/83): Fix generated pagination links when using offset-based pagination
- [#84](https://github.com/woohoolabs/yin/issues/84): Exception codes are always 0

## 4.0.0 - 2019-04-19

This release is the same as 4.0-beta2. The full change set is the following:

ADDED:

- JSON:API 1.1 related features:
    - Partial support for Profiles
    - Support for `type` links in errors
- `Resources` can also use the `$object` property to access the object which is being transformed
- Separate classes for the different types of links instead of the generic `Links` class
    - `DocumentLinks`
    - `ResourceLinks`
    - `RelationshipLinks`
    - `ErrorLinks`
- New capabilities related to pagination:
    - [#70](https://github.com/woohoolabs/yin/issues/70): Better support for query parameters in pagination links
    - `PaginationFactory` class to decouple Pagination class instantiation from the request
    - `JsonApi::getPaginationFactory()` method to make it easier to retrieve the Pagination Factory
    - `FixedCursorBasedPagination` class which preserves the original behaviour of the changed `CursorBasedPagination`
    - `FixedCursorBasedPaginationProviderTrait` for using it in connection with `FixedCursorBasedPagination`
    - `FixedPageBasedPaginationLinkProviderTrait` for using it in connection with `FixedPageBasedPagination`
- New capabilities related to relationships:
    - `ResponseInterface::hasToOneRelationship()` to determine if a specific To-One relationship exists
    - `ResponseInterface::hasToManyRelationship()` to determine if a specific To-Many relationship exists

CHANGED:

- Updated `justinrainbow/json-schema` to v5.2
- `Documents` should use the `$object` property instead of `$domainObject` (__BREAKING CHANGE__)
- Links are used strictly according to the spec (__BREAKING CHANGE__):
    - `AbstractSuccessfulDocument::getLinks()` returns `?DocumentLinks` instead of `?Links`
    - `AbstractErrorDocument::getLinks()` returns `?DocumentLinks` instead of `?Links`
    - `ErrorDocument::getLinks()` returns `?DocumentLinks` instead of `?Links`
    - `ErrorDocument::setLinks()` expects a parameter of `?DocumentLinks` type instead of `?Links`
    - `AbstractResource::getLinks()` returns `?ResourceLinks` instead of `Links`
    - `AbstractRelationship::getLinks()` returns `?RelationshipLinks` instead of `Links`
    - `AbstractRelationship::setLinks()` expects a parameter of `?RelationshipLinks` type instead of `Links`
    - `Error::getLinks()` returns `ErrorLinks` instead of `Links`
    - `Error::setLinks()` expects a parameter of `ErrorLinks` instead of `Links`
- Improvements related to `JsonApiExceptionInterface` (__BREAKING CHANGE__):
    - `JsonApiExceptionInterface` now extends `Throwable`
    - `JsonApiExceptionInterface::getErrorDocument()` must return an `ErrorDocumentInterface` instead of an `AbstractErrorDocument`
- Improvements related to pagination (__BREAKING CHANGE__):
    - A `$defaultSize` constructor parameter was added to `CursorBasedPagination` to define a default value for the `page[size]` query parameter
    - Properties and methods of `FixedPageBasedPagination` became non-nullable
    - Properties and methods of `OffsetBasedPagination` became non-nullable
    - Properties and methods of `PageBasedPagination` became non-nullable
    - Methods of `PaginationLinkProviderInterface` expect a second parameter with a `$queryString` name
- Setting the status code and `Content-Type` header of the JSON:API response is done by the `Responder` by default instead of `Serializer`s (__BREAKING CHANGE__):
    - The `Responder` class sets the status code and the `Content-Type` header of the response, while custom `Serializer`s can override them optionally
    - `SerializerInterface::serialize()` only accepts two arguments instead of 3 as the `$responseCode` parameter was removed
    - `JsonSerializer` does not set the `Content-Type` header and the status code of the response anymore
- Improvements related to relationships (__BREAKING CHANGE__):
    - `ResponseInterface::getToOneRelationship()` throws an exception instead of returning null if the relationship doesn't exist
    - `ResponseInterface::getToManyRelationship()` throws an exception instead of returning null if the relationship doesn't exist
- The `TransformerTrait::fromSqlToIso8601Time()` method expects a `?DateTimeZone` as its second argument instead of `string` (__BREAKING CHANGE__)

REMOVED:

- The generic `Links` class (__BREAKING CHANGE__)
- Methods related to pagination class instantiation were removed from `RequestInterface` (__BREAKING CHANGE__):
    - `RequestInterface::getFixedPageBasedPagination()`
    - `RequestInterface::getPageBasedPagination()`
    - `RequestInterface::getOffsetBasedPagination()`
    - `RequestInterface::getCursorBasedPagination()`
- Various deprecated classes (__BREAKING CHANGE__):
    - `WoohooLabs\Yin\JsonApi\Document\AbstractCollectionDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractCollectionDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\AbstractDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractErrorDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\AbstractSimpleResourceDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractSimpleResourceDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\AbstractSingleResourceDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractSingleResourceDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulResourceDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractSuccessfulResourceDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\ErrorDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\ErrorDocument` instead
    - `WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer`: use `WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource` instead
    - `WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface`: use `WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface` instead
    - `WoohooLabs\Yin\JsonApi\Schema\Error`: use `WoohooLabs\Yin\JsonApi\Schema\Error\Error` instead
    - `WoohooLabs\Yin\JsonApi\Schema\ErrorSource`: use `WoohooLabs\Yin\JsonApi\Schema\Error\ErrorSource` instead
    - `WoohooLabs\Yin\JsonApi\Exception\JsonApiException`: use `WoohooLabs\Yin\JsonApi\Exception\AbstractJsonApiException` instead
    - `WoohooLabs\Yin\JsonApi\Request\Request`: use `WoohooLabs\Yin\JsonApi\Request\JsonApiRequest` instead
    - `WoohooLabs\Yin\JsonApi\Request\RequestInterface`: use `WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface` instead
    - `WoohooLabs\Yin\JsonApi\Schema\Link`: use `WoohooLabs\Yin\JsonApi\Schema\Link\Link` instead
    - `WoohooLabs\Yin\JsonApi\Schema\LinkObject`: use `WoohooLabs\Yin\JsonApi\Schema\Link\LinkObject` instead
- Various deprecated methods (__BREAKING CHANGE__):
    - `AbstractErrorDocument::getResponseCode()` (use `AbstractErrorDocument::getStatusCode()` instead)
    - `RequestValidator::lintBody()` (use `RequestValidator::validateJsonBody()` instead)
    - `ResponseValidator::lintBody()` (use `ResponseValidator::validateJsonBody()` instead)
    - `ResponseValidator::validateBody()` (`ResponseValidator::validateJsonApiBody()`)
- The deprecated `AbstractRelationship::omitWhenNotIncluded()` method (__BREAKING CHANGE__): use `AbstractRelationship::omitDataWhenNotIncluded()`

FIXED:

- Issues with 0 and non-numeric values when using built-in pagination objects (`PageBasedPagination`, `FixedPageBasedPagination`, `OffsetBasedPagination`)
- Various issues found by static analysis
- Query parameters of pagination links were not encoded properly
- The `page` and `filter` query parameters must have an array value as per the spec
- Instead of returning null, an exception is thrown when a non-existent relationship is fetched

## 4.0.0-beta2 - 2019-04-09

REMOVED:

- The deprecated `AbstractRelationship::omitWhenNotIncluded()` method (__BREAKING CHANGE__): use `AbstractRelationship::omitDataWhenNotIncluded()`

FIXED:

- Regression with uninitialized Resource properties
- Regression with the source pointer of the `ResourceTypeUnacceptable` error

## 4.0.0-beta1 - 2019-01-19

ADDED:

- JSON:API 1.1 related features:
    - Partial support for Profiles
    - Support for `type` links in errors
- `Resources` can also use the `$object` property to access the object which is being transformed
- Separate classes for the different types of links instead of the generic `Links` class
    - `DocumentLinks`
    - `ResourceLinks`
    - `RelationshipLinks`
    - `ErrorLinks`
- New capabilities related to pagination:
    - [#70](https://github.com/woohoolabs/yin/issues/70): Better support for query parameters in pagination links
    - `PaginationFactory` class to decouple Pagination class instantiation from the request
    - `JsonApi::getPaginationFactory()` method to make it easier to retrieve the Pagination Factory
    - `FixedCursorBasedPagination` class which preserves the original behaviour of the changed `CursorBasedPagination`
    - `FixedCursorBasedPaginationProviderTrait` for using it in connection with `FixedCursorBasedPagination`
    - `FixedPageBasedPaginationLinkProviderTrait` for using it in connection with `FixedPageBasedPagination`
- New capabilities related to relationships:
    - `ResponseInterface::hasToOneRelationship()` to determine if a specific To-One relationship exists
    - `ResponseInterface::hasToManyRelationship()` to determine if a specific To-Many relationship exists

CHANGED:

- Updated `justinrainbow/json-schema` to v5.2
- `Documents` should use the `$object` property instead of `$domainObject` (__BREAKING CHANGE__)
- Links are used strictly according to the spec (__BREAKING CHANGE__):
    - `AbstractSuccessfulDocument::getLinks()` returns `?DocumentLinks` instead of `?Links`
    - `AbstractErrorDocument::getLinks()` returns `?DocumentLinks` instead of `?Links`
    - `ErrorDocument::getLinks()` returns `?DocumentLinks` instead of `?Links`
    - `ErrorDocument::setLinks()` expects a parameter of `?DocumentLinks` type instead of `?Links`
    - `AbstractResource::getLinks()` returns `?ResourceLinks` instead of `Links`
    - `AbstractRelationship::getLinks()` returns `?RelationshipLinks` instead of `Links`
    - `AbstractRelationship::setLinks()` expects a parameter of `?RelationshipLinks` type instead of `Links`
    - `Error::getLinks()` returns `ErrorLinks` instead of `Links`
    - `Error::setLinks()` expects a parameter of `ErrorLinks` instead of `Links`
- Improvements related to `JsonApiExceptionInterface` (__BREAKING CHANGE__):
    - `JsonApiExceptionInterface` now extends `Throwable`
    - `JsonApiExceptionInterface::getErrorDocument()` must return an `ErrorDocumentInterface` instead of an `AbstractErrorDocument`
- Improvements related to pagination (__BREAKING CHANGE__):
    - A `$defaultSize` constructor parameter was added to `CursorBasedPagination` to define a default value for the `page[size]` query parameter
    - Properties and methods of `FixedPageBasedPagination` became non-nullable
    - Properties and methods of `OffsetBasedPagination` became non-nullable
    - Properties and methods of `PageBasedPagination` became non-nullable
    - Methods of `PaginationLinkProviderInterface` expect a second parameter with a `$queryString` name
- Setting the status code and `Content-Type` header of the JSON:API response is done by the `Responder` by default instead of `Serializer`s (__BREAKING CHANGE__):
    - The `Responder` class sets the status code and the `Content-Type` header of the response, while custom `Serializer`s can override them optionally
    - `SerializerInterface::serialize()` only accepts two arguments instead of 3 as the `$responseCode` parameter was removed
    - `JsonSerializer` does not set the `Content-Type` header and the status code of the response anymore
- Improvements related to relationships (__BREAKING CHANGE__):
    - `ResponseInterface::getToOneRelationship()` throws an exception instead of returning null if the relationship doesn't exist
    - `ResponseInterface::getToManyRelationship()` throws an exception instead of returning null if the relationship doesn't exist
- The `TransformerTrait::fromSqlToIso8601Time()` method expects a `?DateTimeZone` as its second argument instead of `string` (__BREAKING CHANGE__)

REMOVED:

- The generic `Links` class (__BREAKING CHANGE__)
- Methods related to pagination class instantiation were removed from `RequestInterface` (__BREAKING CHANGE__):
    - `RequestInterface::getFixedPageBasedPagination()`
    - `RequestInterface::getPageBasedPagination()`
    - `RequestInterface::getOffsetBasedPagination()`
    - `RequestInterface::getCursorBasedPagination()`
- Various deprecated classes (__BREAKING CHANGE__):
    - `WoohooLabs\Yin\JsonApi\Document\AbstractCollectionDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractCollectionDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\AbstractDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractErrorDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\AbstractSimpleResourceDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractSimpleResourceDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\AbstractSingleResourceDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractSingleResourceDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulResourceDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractSuccessfulResourceDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\ErrorDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\ErrorDocument` instead
    - `WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer`: use `WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource` instead
    - `WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface`: use `WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface` instead
    - `WoohooLabs\Yin\JsonApi\Schema\Error`: use `WoohooLabs\Yin\JsonApi\Schema\Error\Error` instead
    - `WoohooLabs\Yin\JsonApi\Schema\ErrorSource`: use `WoohooLabs\Yin\JsonApi\Schema\Error\ErrorSource` instead
    - `WoohooLabs\Yin\JsonApi\Exception\JsonApiException`: use `WoohooLabs\Yin\JsonApi\Exception\AbstractJsonApiException` instead
    - `WoohooLabs\Yin\JsonApi\Request\Request`: use `WoohooLabs\Yin\JsonApi\Request\JsonApiRequest` instead
    - `WoohooLabs\Yin\JsonApi\Request\RequestInterface`: use `WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface` instead
    - `WoohooLabs\Yin\JsonApi\Schema\Link`: use `WoohooLabs\Yin\JsonApi\Schema\Link\Link` instead
    - `WoohooLabs\Yin\JsonApi\Schema\LinkObject`: use `WoohooLabs\Yin\JsonApi\Schema\Link\LinkObject` instead
- Various deprecated methods (__BREAKING CHANGE__):
    - `AbstractErrorDocument::getResponseCode()` (use `AbstractErrorDocument::getStatusCode()` instead)
    - `RequestValidator::lintBody()` (use `RequestValidator::validateJsonBody()` instead)
    - `ResponseValidator::lintBody()` (use `ResponseValidator::validateJsonBody()` instead)
    - `ResponseValidator::validateBody()` (`ResponseValidator::validateJsonApiBody()`)

FIXED:

- Issues with 0 and non-numeric values when using built-in pagination objects (`PageBasedPagination`, `FixedPageBasedPagination`, `OffsetBasedPagination`)
- Various issues found by static analysis
- Query parameters of pagination links were not encoded properly
- The `page` and `filter` query parameters must have an array value as per the spec
- Instead of returning null, an exception is thrown when a non-existent relationship is fetched

## 3.1.1 - 2019-04-18

DEPRECATED:

- `ToOneRelationship::omitWhenNotIncluded()`: Use `ToOneRelationship::omitDataWhenNotIncluded()`
- `ToManyRelationship::omitWhenNotIncluded()`: Use `ToManyRelationship::omitDataWhenNotIncluded()`

## 3.1.0 - 2019-01-17

This is a release with several deprecations in order to ensure forward compatibility with Yin 4.0.

DEPRECATED:

- Classes related to Documents:
    - `WoohooLabs\Yin\JsonApi\Document\AbstractCollectionDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractCollectionDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\AbstractDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractErrorDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\AbstractSimpleResourceDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractSimpleResourceDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\AbstractSingleResourceDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractSingleResourceDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulResourceDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\AbstractSuccessfulResourceDocument` instead
    - `WoohooLabs\Yin\JsonApi\Document\ErrorDocument`: use `WoohooLabs\Yin\JsonApi\Schema\Document\ErrorDocument` instead
- Classes related to Resources:
    - `WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer`: use `WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource` instead
    - `WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface`: use `WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface` instead
- Classes related to Errors and Exceptions:
    - `WoohooLabs\Yin\JsonApi\Schema\Error`: use `WoohooLabs\Yin\JsonApi\Schema\Error\Error` instead
    - `WoohooLabs\Yin\JsonApi\Schema\ErrorSource`: use `WoohooLabs\Yin\JsonApi\Schema\Error\ErrorSource` instead
    - `WoohooLabs\Yin\JsonApi\Exception\JsonApiException`: use `WoohooLabs\Yin\JsonApi\Exception\AbstractJsonApiException` instead
- Classes related to the Request:
    - `WoohooLabs\Yin\JsonApi\Request\Request`: use `WoohooLabs\Yin\JsonApi\Request\JsonApiRequest` instead
    - `WoohooLabs\Yin\JsonApi\Request\RequestInterface`: use `WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface` instead
- Classes related to Links:
    - `WoohooLabs\Yin\JsonApi\Schema\Link`: use `WoohooLabs\Yin\JsonApi\Schema\Link\Link` instead
    - `WoohooLabs\Yin\JsonApi\Schema\LinkObject`: use `WoohooLabs\Yin\JsonApi\Schema\Link\LinkObject` instead
- The following methods:
    - `AbstractErrorDocument::getResponseCode()`: use `AbstractErrorDocument::getStatusCode()` instead
    - `RequestValidator::lintBody()`: use `RequestValidator::validateJsonBody()` instead
    - `ResponseValidator::lintBody()`: use `ResponseValidator::validateJsonBody()` instead
    - `ResponseValidator::validateBody()`: use `ResponseValidator::validateJsonApiBody()` instead

## 3.0.2 - 2018-02-06

FIXED:

- [#69](https://github.com/woohoolabs/yin/pull/69): Fatal error when providing invalid types in `fields`, `include` and `sort` query parameters

## 3.0.1 - 2018-02-02

CHANGED:

- Return the `included` array even when it is empty if the `include` parameter is supplied
- PHPUnit 7.0 is minimally required to run tests

FIXED:

- [#66](https://github.com/woohoolabs/yin/issues/66): Bug in request header validation
- [#68](https://github.com/woohoolabs/yin/pull/68): Fix fatal error when resource ID is not a string

## 3.0.0 - 2017-11-21

CHANGED:

- Increased minimum PHP version requirement to 7.1
- `ExceptionFactoryInterface` methods must return `JsonApiExceptionInterface` (__BREAKING CHANGE__)
- `AbstractDocument::getJsonApi()` and `AbstractDocument::getLinks()` return types must be declared (__BREAKING CHANGE__)
- `ResourceTransformerInterface::getLinks()` return type must be declared (__BREAKING CHANGE__)

REMOVED:

- `TransformerTrait::toBool()` and `TransformerTrait::toInt()` methods

FIXED:

- Some minor type declaration-related issues

## 3.0.0-beta1 - 2017-09-14

CHANGED:

- Increased minimum PHP version requirement to 7.1
- `ExceptionFactoryInterface` methods must return `JsonApiExceptionInterface` (__BREAKING CHANGE__)
- `AbstractDocument::getJsonApi()` and `AbstractDocument::getLinks()` return types must be declared (__BREAKING CHANGE__)
- `ResourceTransformerInterface::getLinks()` return type must be declared (__BREAKING CHANGE__)

REMOVED:

- `TransformerTrait::toBool()` and `TransformerTrait::toInt()` methods

FIXED:

- Some minor type declaration-related issues

## 2.0.6 - 2018-02-06

FIXED:

- [#69](https://github.com/woohoolabs/yin/pull/69): Fatal error when providing invalid types in `fields`, `include` and `sort` query parameters

## 2.0.5 - 2018-01-31

FIXED:

- [#68](https://github.com/woohoolabs/yin/pull/68): Fatal error when resource ID is not string

## 2.0.4 - 2017-09-13

ADDED:

- Possibility to define the `$code` constructor argument of `Exception`s when instantiating `JsonApiException`s

## 2.0.3 - 2017-08-24

CHANGED:

- Updated JSON:API schema to the latest version

FIXED:

- [#64](https://github.com/woohoolabs/yin/issues/64): Body sent via POST is not retrievable

## 2.0.2 - 2017-06-13

CHANGED:

- Updated JSON:API schema to the latest version

## 2.0.1 - 2017-04-18

ADDED:

- Possibility to configure the displayed time zone when using `TransformerTrait::toIso8601Date()` and `TransformerTrait::toIso8601DateTime()`

CHANGED:

- Updated JSON:API schema to the latest version

## 2.0.0 - 2017-03-10

ADDED:

- `Responder::okWithRelationship()` and `Responder::createdWithRelationship()`
- [#58](https://github.com/woohoolabs/yin/issues/58): Allow to set options to the json_encode method
- Support for custom deserializers
- [#57](https://github.com/woohoolabs/yin/issues/57): Support for validating the request during hydration

CHANGED:

- Increased minimum PHP version requirement to 7.0
- Documents, Transformers, Hydrators, Serializers and Exceptions must be type hinted strictly (__BREAKING CHANGE__)
- [#51](https://github.com/woohoolabs/yin/pull/55): Decouple `AbstractSuccessfulDocument` from `Serializer` and `Response` (__BREAKING CHANGE__)
- Renamed `JsonApi` object to `JsonApiObject` in order to avoid ambiguities (__BREAKING CHANGE__)
- Renamed `DefaultSerializer` to `JsonSerializer` (__BREAKING CHANGE__)
- Renamed some methods of `ExceptionFactoryInterface` which didn't end with `Exception` (e.g. `createRelationshipNotExists()` to `createRelationshipNotExistsException()`) (__BREAKING CHANGE__)
- Hydrators must implement the `validateRequest()` method (__BREAKING CHANGE__)
- `HydratorTrait::getAcceptedType()` was renamed to `HydratorTrait::getAcceptedTypes()` and it should always return an array
even if the hydrator can only accept one resource type (__BREAKING CHANGE__)

REMOVED:

- `RelationshipResponder::okWithMeta()` method (__BREAKING CHANGE__)
- `JsonApi::respondWithRelationship()` method (__BREAKING CHANGE__)

FIXED:

- [#59](https://github.com/woohoolabs/yin/issues/59): Resource schema validating
- Minor problems with request/response validators
- Minor bug fixes

## 2.0.0-rc1 - 2017-03-06

ADDED:

- [#57](https://github.com/woohoolabs/yin/issues/57): Support for validating the request during hydration

CHANGED:

- Hydrators must implement the `validateRequest()` method (__BREAKING CHANGE__)

## 2.0.0-beta2 - 2017-02-15

ADDED:

- Support for custom deserializers

CHANGED:

- Renamed `DefaultSerializer` to `JsonSerializer` (__BREAKING CHANGE__)
- Renamed some methods of `ExceptionFactoryInterface` which didn't end with `Exception` (e.g. `createRelationshipNotExists()` to `createRelationshipNotExistsException()`) (__BREAKING CHANGE__)

FIXED:

- [#59](https://github.com/woohoolabs/yin/issues/59): Resource schema validating
- Minor problems with request/response validators

## 2.0.0-beta1 - 2017-02-09

ADDED:

- `Responder::okWithRelationship()` and `Responder::createdWithRelationship()`
- [#58](https://github.com/woohoolabs/yin/issues/58): Allow to set options to the json_encode method

CHANGED:

- Increased minimum PHP version requirement to 7.0
- Documents, Transformers, Hydrators, Serializers and Exceptions must be type hinted strictly (__BREAKING CHANGE__)
- [#51](https://github.com/woohoolabs/yin/pull/55): Decouple `AbstractSuccessfulDocument` from `Serializer` and `Response` (__BREAKING CHANGE__)
- Renamed `JsonApi` object to `JsonApiObject` in order to avoid ambiguities (__BREAKING CHANGE__)

REMOVED:

- `RelationshipResponder::okWithMeta()` method (__BREAKING CHANGE__)
- `JsonApi::respondWithRelationship()` method (__BREAKING CHANGE__)

FIXED:

- Minor bug fixes

## 1.0.6 - 2017-02-28

FIXED:

- [#60](https://github.com/woohoolabs/yin/pull/60): Fixed datetime format method from sql without second argument

## 1.0.5 - 2017-02-11

ADDED:

- Possibility to configure the `DefaultSerializer`

FIXED:

- `AbstractSimpleResourceDocument::getRelationshipContent()` didn't return any value

## 1.0.4 - 2016-02-02

FIXED:

- Fixed status code of multiple error responses

## 1.0.3 - 2016-12-21

ADDED:

- Better support for "about" links

FIXED:

- Error status codes are now represented as string as per the spec
- `TransformerTrait()` datetime transformer methods identify the ISO-8601 format correctly

## 1.0.2 - 2016-11-17

CHANGED:

- [#51](https://github.com/woohoolabs/yin/issues/51): Remove sorting of included resources

## 1.0.1 - 2016-11-07

FIXED:

- [#50](https://github.com/woohoolabs/yin/issues/50): Omitting `data` property from `relationships` information

## 1.0.0 - 2016-10-29

ADDED:

- [#19](https://github.com/woohoolabs/yin/issues/19): Support for custom serializers
- Support for using `Collection`s not implementing `ArrayAccess` in `AbstractCollectionDocument`s
- Docker Compose file to run tests more easily

CHANGED:

- Increased minimum PHP version requirement to PHP 5.6
- Made `$exceptionFactory` and `$serializer` constructor parameters optional for the `JsonApi` class
- Updated JSON:API schema to the latest version
- Renamed pagination provider traits to pagination link provider traits
- Renamed pagination objects to include "Based" (e.g.: `PagePagination` became `PageBasedPagination`)
- Improved documentation: added missing sections and fixed a lot of stylistic errors
- Improved test coverage

FIXED:

- `FixedPageBasedPagination::getPage()`, `PageBasedPagination::getPage()` and `PageBasedPagination::getSize()`
now return `integer` instead of `string`
- `CursorBasedPagination::getCursor()` now returns `integer` instead of `string`
- `OffsetBasedPagination::getOffset()` and `OffsetBasedPagination::getLimit()` now return `integer` instead of `string`
- [#44](https://github.com/woohoolabs/yin/pull/44): Fixed request validation
- [#45](https://github.com/woohoolabs/yin/pull/45): `Request` class uses exception factory to throw exception
- [#48](https://github.com/woohoolabs/yin/issue/48): PageBasedPaginationProviderTrait getPrevLink and getNextLink generate wrong links

## 0.11.0 - 2016-08-16

ADDED:

- [#15](https://github.com/woohoolabs/yin/issues/15): PATCH a relationship with {"data":null}
- [#33](https://github.com/woohoolabs/yin/pull/33): Add support for clearing relationships

CHANGED:

- Default values can now be defined to several methods of `Request`
- Slightly optimized request body serialization
- Renamed `Request::getResourceToOneRelationship()` to `Request::getToOneRelationship()`
- Renamed `Request::getResourceToManyRelationship()` to `Request::getToManyRelationship()`
- Changed the signature of the `Request` constructor from `__construct(ServerRequestInterface $request)` to `__construct(ServerRequestInterface $request, ExceptionFactoryInterface $exceptionFactory)`

REMOVED:

- Support for extensions

FIXED:

- [#30](https://github.com/woohoolabs/yin/issues/30): `ResourceIdentifier::fromArray()` returning null is not handled gracefully
- `MediaTypeUnacceptable` and `MediaTypeUnsupported` exception messages

## 0.10.8 - 2016-07-05

FIXED:

- [#28](https://github.com/woohoolabs/yin/issues/28): Hydrate attribute with null

## 0.10.7 - 2016-06-13

FIXED:

- [#25](https://github.com/woohoolabs/yin/issues/25): OffsetPagination bug, offset and limit mixup

## 0.10.6 - 2016-05-17

CHANGED:

- Updated justinrainbow/json-schema to v2.0.0

FIXED:

- [#23](https://github.com/woohoolabs/yin/issues/23): Fixed jsonApi object

## 0.10.5 - 2016-05-08

ADDED:

- Support for PHPUnit 5.0
- `Request::getFilteringParam()` method

CHANGED:

- Updated JSON API schema
- A default value can be provided to `Request::getResourceAttribute()` method when the attribute is not present
- [#20](https://github.com/woohoolabs/yin/issues/20): Expressing empty relationships in the response

FIXED:

- [#22](https://github.com/woohoolabs/yin/issues/22): Data member isn't present when fetching a relationship

## 0.10.4 - 2016-03-29

FIXED:

- [#18](https://github.com/woohoolabs/yin/issues/18): Sorting always happens on primary key in resource

## 0.10.3 - 2016-03-26

ADDED:

- Integrations section to the read me file

FIXED:

- Application errors now have status code 500 instead of 400
- [#17](https://github.com/woohoolabs/yin/pull/17): Avoid double stream reading

## 0.10.2 - 2016-02-29

ADDED:

- Missing sections to the read me file

CHANGED:

- [#8](https://github.com/woohoolabs/yin/issues/8): Pass attribute name to the attribute transformer
- [#11](https://github.com/woohoolabs/yin/pull/11): Pass relationship name to the relationship transformer
- [#10](https://github.com/woohoolabs/yin/pull/10): Pass attribute name to the attribute hydrator closure
- [#13](https://github.com/woohoolabs/yin/pull/13): Pass relationship name to the relationship hydrator
- [#14](https://github.com/woohoolabs/yin/pull/14): Expect callables instead of closures for hydrators/transformers
- [#7](https://github.com/woohoolabs/yin/issues/7): More intuitive example URL-s

FIXED:

- [#6](https://github.com/woohoolabs/yin/issues/6): Fixed examples in order not to throw fatal error
- [#16](https://github.com/woohoolabs/yin/issues/16): ResourceIdentifier does not consider "data" key

## 0.10.1 - 2016-01-21

FIXED:

- [#5](https://github.com/woohoolabs/yin/issues/5): Attributes and relationships objects are now omitted when empty instead of being serialized as empty arrays

## 0.10.0 - 2016-01-16

This version incorporates some new methods to easily retrieve the content of the request body and some important bug
fixes. It doesn't contain any breaking changes so updating to v10.0 is highly recommended.

ADDED:

- `AbstractSimpleResourceDocument` to define documents containing information about a single resource without
the need of a resource transformer
- `ClientGeneratedIdRequired` exception
- `getResourceAttributes()` method to `RequestInterface`
- `getResourceToOneRelationship()` and `getResourceToManyRelationship()` methods to `RequestInterface`

CHANGED:

- `TransformerTrait` transformations are now type hinted against `DateTimeInterface` to support `DateTimeImmutable`

FIXED:

- Parameter order in `AbstractCreateHydrator::hydrate()`
- [#3](https://github.com/woohoolabs/yin/issues/3): Fixed multi-level relationships
- Issue when include query param is an empty string

## 0.9.0 - 2015-11-26

ADDED:

- Possibility to pass additional meta information for documents when fetching the response
- [#2](https://github.com/woohoolabs/yin/issues/2): Possibility to only load relationship data when the relationship itself is included

CHANGED:

- Renamed `getDefaultRelationships()` to `getDefaultIncludedRelationships()` in transformers to better reflect its meaning
- The "data" key of relationships won't be present in the response when it is empty
- Renamed `Links::addLinks()` to `Links::setLinks()` and `Links::addLink()` to `Links::setLink()`

REMOVED:

- Most of the `Links::create*` static methods to simplify creation
- `RelativeLinks` class as it became useless

FIXED:

- `Responder::getDocumentResourceResponse()` was wrongly called statically
- PHP version constraint in composer.json

## 0.8.0 - 2015-11-16

ADDED:

- Attributes of the resource in the request body can be retrieved easier
- Even better support for relative links via the `RelativeLinks` class

CHANGED:

- ID of the hydrated resource also gets validated when it is missing
- The provided `ExceptionFactory` can be used when validating client-generated ID-s for hydration
- Renamed `RequestInterface::getBodyData*` methods to `RequestInterface::getResource*`

FIXED:

- Methods of `TransformerTrait` were intended to be non-static

## 0.7.1 - 2015-10-05

ADDED:

- `ApplicationError` and `ResourceNotFound`
- Mentioning optional Composer dependencies in the readme

## 0.7.0 - 2015-10-04

ADDED:

- A separate responder class
- `ExceptionFactoryInterface` which helps you to fully customize error messages
- `JsonApi::hydrate()` helper method to make hydration easier
- Integrated content negotiation and request/response validation from Woohoo Labs. Yin-Middleware
- Even more extensive documentation

CHANGED:

- JSON API exceptions extend `JsonApiException` thus they can be catched easier
- Documents are moved to `JsonApi\Document` namespace from `JsonApi\Transfomer`
- Refactored transformation to fix inclusion of multiple identical resource objects
- When the data member is missing from the top source, the appropriate exception is thrown

REMOVED:

- Different types of responses (e.g.: `FetchResponse`)

FIXED:

- Compound documents now can't include more than one resource object for each type and id pair
- Request body was always null
- Single resource documents didn't contain the data top-level member unless resource ID was 1

## 0.6.0 - 2015-09-22

ADDED:

- More convenient handling of inappropriate relationship types during hydration
- Much more unit tests (320+ tests, 92% coverage)
- Better and more documentation

CHANGED:

- Simplified relative links
- Included resources are now sorted by type and id
- Renamed `AbstractCompoundDocument` to `AbstractSuccessfulDocument`
- Documents now require a `ResourceTransformerInterface` instance instead of `AbstractResourceTransformer`

FIXED:

- Meta data didn't appear in error objects
- Empty version information appeared in jsonApi object
- Constructors of `ToOneRelationships` and `ToManyRelationships` were messed up
- Getters in `MediaTypeUnacceptable` and `MediaTypeUnsupported` didn't return the media type name
- Pagination objects are now correctly instantiated from query parameters
- Validation of query parameters didn't work
- Getting the list of included relationships didn't work as expected
- Status code of error responses was always "500" when the document contained multiple errors
- Content-Type header media types of responses are now correctly assembled when using extensions
- Fatal error when the hydrated resource type didn't match the acceptable type
- Various issues of pagination providers

## 0.5.0 - 2015-09-11

ADDED:

- Support for much easier generation of pagination links
- Shortcut to get the resource ID in `AbstractSingleResourceDocument`
- Support for relative URI-s

CHANGED:

- Improved transformation performance
- Included resources are now sorted by type instead of ID

REMOVED:

- `RelationshipRequest` became useless, thus it was removed

FIXED:
- Instantiation of `Request` could take significant time
- Sparse fieldsets and inclusion of relationships are now fully compliant with the spec
- Links with null value can be included in the response

## 0.4.2 - 2015-08-27

FIXED:

- Some exceptions had errorous namespaces
- `Request::with*` methods returned an instance of PSR `ServerRequestInterface`
- Validation of the `Content-Type` and the `Accept` headers is now compliant with the spec

## 0.4.0 - 2015-08-26

ADDED:

- Support for proper content negotiation
- Support for validation of query parameters
- Support for retrieving the requested extensions
- Full replacement and removal of relationships can be prohibited
- Exception can be raised when an unrecognized sorting parameter is received

CHANGED:

- `CreateHydrator` was renamed to `AbstractCreateHydrator`
- `UpdateHydrator` was renamed to `AbstractUpdateHydrator`
- `AbstractHydrator` can be used for update and create requests too
- Improved and more extensive documentation

FIXED:

- Meta responses follow the specification

## 0.3.6 - 2015-08-19

REMOVED:

- `TransformableInterface` and `SimpleTransformableInterface` as they were unnecessary

FIXED:

- Fixed issue with possible request body parsing
- The included key is not sent if it is empty
- Do not mutate the original responses
- `LinksTrait` and `MetaTrait` support retrieval of their properties
- The response body is cleared before assembling the response
- Errors now don't contain null fields
- Errors can contain links and a source
- Automatically determine the status code of an error document if it is not explicitly set

## 0.3.0 - 2015-08-16

ADDED:

- Support for creation and update of resources via `Hydrators`
- `JsonApi` class
- Response classes
- `Link::getHref()` method

CHANGED:

- `RequestInterface` extends `PSR\Http\Message\ServerRequestInterface`
- Several methods of `AbstractDocument` became public instead of protected
- Substantially refactored and improved examples

## 0.2.0 - 2015-08-01

ADDED:

- Support for proper and automatic fetching of relationships
- Convenience methods for `AbstractResourceTransformer` to support transformation
- Convenience methods for links and relationships
- Examples about relationships

CHANGED:

- Decoupled `Request` from PSR-7 `ServerRequestInterface`
- Simplified document creation and transformation
- Renamed `Criteria` to `Request` for future purposes
- Renamed `OneToManyTraversableRelationship` to `ToManyRelationship`
- Renamed `OneToOneRelationship` to `ToOneRelationship`

REMOVED:

- `CompulsoryLinks` and `PaginatedLinks`

FIXED:

- Transformation of resource relationships
- Transformation of meta element at the top level
- Transformation of null resources

## 0.1.5 - 2015-07-15

ADDED:

- Examples

FIXED:

- Processing of sparse fieldsets
- Processing of included relationships
- Transformation of JsonApi and meta objects

## 0.1.0 - 2015-07-13

- Initial release
