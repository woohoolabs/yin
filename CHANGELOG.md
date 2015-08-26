## 0.5.0 - Unreleased

ADDED:

CHANGED:

FIXED:

## 0.4.0 - 201-08-26

ADDED:

- support for proper content negotiation
- support for validation of query parameters
- support for retrieving the requested extensions
- full replacement and removal of relationships can be prohibited
- exception can be raised when an unrecognized sorting parameter is received

CHANGED:

- `CreateHydrator` was renamed to `AbstractCreateHydrator`
- `UpdateHydrator` was renamed to `AbstractUpdateHydrator`
- `AbstractHydrator` can be used for update and create requests too
- improved and more extensive documentation

FIXED:

- meta responses follow the specification

## 0.3.6 - 2015-08-19

REMOVED:

- `TransformableInterface` and `SimpleTransformableInterface` as they were unnecessary

FIXED:

- fixed issue with possible request body parsing
- the included key is not sent if it is empty
- do not mutate the original responses
- `LinksTrait` and `MetaTrait` support retrieval of their properties
- the response body is cleared before assembling the response
- errors now don't contain null fields
- errors can contain links and a source
- automatically determine the status code of an error document if it is not explicitly set

## 0.3.0 - 2015-08-16

ADDED:

- support for creation and update of resources via `Hydrators`
- `JsonApi` class
- response classes
- `Link::getHref()` method

CHANGED:

- `RequestInterface` extends `PSR\Http\Message\ServerRequestInterface`
- several methods of `AbstractDocument` became public instead of protected
- substantially refactored and improved examples

## 0.2.0 - 2015-08-01

ADDED:

- support for proper and automatic fetching of relationships
- convenience methods to `AbstractResourceTransformer` to support transformation
- convenience methods for links and relationships
- examples about relationships

CHANGED:

- decoupled `Request` from PSR-7 `ServerRequestInterface`
- simplified document creation and transformation
- renamed `Criteria` to `Request` for future purposes
- renamed `OneToManyTraversableRelationship` to `ToManyRelationship`
- renamed `OneToOneRelationship` to `ToOneRelationship`

REMOVED:

- `CompulsoryLinks` and `PaginatedLinks`

FIXED:

- transformation of resource relationships
- transformation of meta element at the top level
- transformation of null resources

## 0.1.5 - 2015-07-15

ADDED:

- examples

FIXED:

- processing of sparse fieldsets
- processing of included relationships
- transformation of JsonApi and meta objects

## 0.1.0 - 2015-07-13

- Initial release
