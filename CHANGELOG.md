## 0.8.0 - unreleased

ADDED:

CHANGED:

REMOVED:

FIXED:

## 0.7.0 - 2015-10-04

ADDED:
- A separate responder class
- `ExceptionFactoryInterface` which helps you to fully customize error messages
- `JsonApi::hydrate()` helper method to make hydration easier
- Integrated content negotiation and request/response validation from Woohoo Labs. Yin-Middlewares
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
