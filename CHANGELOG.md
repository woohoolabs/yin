# Change Log

### 0.4.0 - Unreleased

#### Added
- Support for proper content negotiation
- Support for validation of query parameters
- Support for retrieving the requested extensions
- Full replacement and removal of relationships can be prohibited
- Exception can be raised when an unrecognized sorting parameter is received

#### Changed
- `CreateHydratior` was renamed to `AbstractCreateHydrator`
- `UpdateHydratior` was renamed to `AbstractUpdateHydrator`
- `AbstractHydrator` can be used for update and create requests too
- Improved and more extensive documentation

#### Fixed
- Meta responses follow the specification

### 0.3.6 - 2015-08-19

#### Fixed
- Fixed issue with possible request body parsing

### 0.3.5 - 2015-08-19

#### Fixed
- The included key is not sent if it is empty

### 0.3.4 - 2015-08-18

#### Fixed
- Do not mutate the original responses

### 0.3.3 - 2015-08-18

#### Changed
- `LinksTrait` and `MetaTrait` support retrieval of their properties

#### Removed
- `TransformableInterface` and `SimpleTransformableInterface` as they were useless

#### Fixed
- The response body is cleared before assembling the response

### 0.3.2 - 2015-08-17

#### Fixed
- Errors now don't contain null fields
- Errors can contain links and a source

### 0.3.1 - 2015-08-17

#### Fixed
- Automatically determine the status code of an error document if it is not explicitly set

### 0.3.0 - 2015-08-16

#### Added
- Support for creation and update of resources via `Hydrators`
- `JsonApi` class
- Response classes
- `Link::getHref()` method

#### Changed
- `RequestInterface` extends `PSR\Http\Message\ServerRequestInterface`
- Several methods of `AbstractDocument` became public instead of protected
- Substantially refactored and improved examples

### 0.2.0 - 2015-08-01

#### Added
- Support for proper and automatic fetching of relationships
- Convenience methods to `AbstractResourceTransformer` to support transformation
- Convenience methods for links and relationships
- Examples about relationships

#### Changed
- Decoupled `Request` from PSR-7 `ServerRequestInterface`
- Simplified document creation and transformation
- Renamed `Criteria` to `Request` for future purposes
- Renamed `OneToManyTraversableRelationship` to `ToManyRelationship`
- Renamed `OneToOneRelationship` to `ToOneRelationship`

#### Removed
- `CompulsoryLinks` and `PaginatedLinks`

#### Fixed
- Transformation of resource relationships
- Transformation of meta element at the top level
- Transformation of null resources

### 0.1.5 - 2015-07-15

#### Added
- Examples

#### Fixed
- Processing of sparse fieldsets
- Processing of included relationships
- Transformation of JsonApi and meta objects

### 0.1.0 - 2015-07-13
- Initial version
