# Change Log

### 0.2.0 - Unreleased

#### Added
- Support for proper and automatic fetching of relationships
- Convenience methods to ``AbstractResourceTransformer`` to support transformation
- Convenience methods for links and relationships

#### Changed
- Simplified document creation and transformation
- Renamed ``Criteria`` to ``Request`` for future purposes
- Renamed ``OneToManyTraversableRelationship`` to ``ToManyRelationship``
- Renamed ``OneToOneRelationship`` to ``ToOneRelationship``

#### Removed
- ``CompulsoryLinks`` and ``PaginatedLinks``

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
