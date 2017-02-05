<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request;

use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Request\Pagination\CursorBasedPagination;
use WoohooLabs\Yin\JsonApi\Request\Pagination\FixedPageBasedPagination;
use WoohooLabs\Yin\JsonApi\Request\Pagination\OffsetBasedPagination;
use WoohooLabs\Yin\JsonApi\Request\Pagination\PageBasedPagination;

interface RequestInterface extends ServerRequestInterface
{
    /**
     * Validates if the current request's Content-Type header conforms to the JSON API schema.
     *
     * @throws MediaTypeUnsupported
     */
    public function validateContentTypeHeader();

    /**
     * Validates if the current request's Accept header conforms to the JSON API schema.
     *
     * @throws MediaTypeUnacceptable
     */
    public function validateAcceptHeader();

    /**
     * Validates if the current request's query parameters conform to the JSON API schema.
     *
     * According to the JSON API specification "Implementation specific query parameters MUST
     * adhere to the same constraints as member names with the additional requirement that they
     * MUST contain at least one non a-z character (U+0061 to U+007A)".
     *
     * @throws QueryParamUnrecognized
     */
    public function validateQueryParams();

    /**
     * Returns a list of field names for the given resource type which are required to be present in the response.
     */
    public function getIncludedFields(string $resourceType): array;

    /**
     * Determines if a given field for a given resource type should be present in the response or not.
     */
    public function isIncludedField(string $resourceType, string $field): bool;

    /**
     * Determines if the request needs any relationships to be included.
     */
    public function hasIncludedRelationships(): bool;

    /**
     * Returns a list of relationship paths for a given parent path.
     */
    public function getIncludedRelationships(string $baseRelationshipPath): array;

    /**
     * Determines if a given relationship name that is a child of the $baseRelationshipPath is required to be included
     * in the response.
     */
    public function isIncludedRelationship(
        string $baseRelationshipPath,
        string $relationshipName,
        array $defaultRelationships
    ): bool;

    public function getSorting(): array;

    public function getPagination(): array;

    public function getFixedPageBasedPagination(int $defaultPage = null): FixedPageBasedPagination;

    public function getPageBasedPagination(int $defaultPage = null, int $defaultSize = null): PageBasedPagination;

    public function getOffsetBasedPagination(
        int $defaultOffset = null,
        int $defaultLimit = null
    ): OffsetBasedPagination;

    /**
     * @param mixed $defaultCursor
     */
    public function getCursorBasedPagination($defaultCursor = null): CursorBasedPagination;

    public function getFiltering(): array;

    /**
     * @param mixed|null $default
     * @return string|mixed
     */
    public function getFilteringParam(string $param, $default = null);

    /**
     * Returns a query parameter with a name of $name if it is present in the request, or the $default value otherwise.
     *
     * @param mixed $default
     * @return array|string|mixed
     */
    public function getQueryParam(string $name, $default = null);

    /**
     * Returns a query parameter with a name of $name if it is present in the request, or the $default value otherwise.
     *
     * @param mixed $value
     * @return $this
     */
    public function withQueryParam(string $name, $value);

    /**
     * Returns the "data" part of the request if it is present in the body, or null otherwise.
     *
     * @param mixed $default
     * @return array|mixed
     */
    public function getResource($default = null);

    /**
     * Returns the "type" key's value in the "data" part of the request if it is present in the body, or null otherwise.
     *
     * @param mixed $default
     * @return string|mixed
     */
    public function getResourceType($default = null);

    /**
     * Returns the "id" key's value in the "data" part of the request if it is present in the body, or null otherwise.
     *
     * @param mixed $default
     * @return string|mixed
     */
    public function getResourceId($default = null);

    public function getResourceAttributes(): array;

    /**
     * @param mixed $default
     * @return mixed
     */
    public function getResourceAttribute(string $attribute, $default = null);

    /**
     * @return ToOneRelationship|null
     */
    public function getToOneRelationship(string $relationship);

    /**
     * @return ToManyRelationship|null
     */
    public function getToManyRelationship(string $relationship);
}
