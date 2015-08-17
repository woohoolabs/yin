# Change Log

### 0.4.0 - Unreleased

### 0.3.2 - 2015-08-17

#### Fixed
- Errors now don't contain null fields

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
