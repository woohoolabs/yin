<?php
namespace WoohooLabs\Yin\JsonApi\Request;

use Psr\Http\Message\ServerRequestInterface;

interface RequestInterface extends ServerRequestInterface
{
    /**
     * Validates if the current request's Content-Type header conforms to the JSON API schema.
     *
     * @throws \WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported
     */
    public function validateContentTypeHeader();

    /**
     * Validates if the current request's Accept header conforms to the JSON API schema.
     *
     * @throws \WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable
     */
    public function validateAcceptHeader();

    /**
     * Validates if the current request's query parameters conform to the JSON API schema.
     *
     * According to the JSON API specification "Implementation specific query parameters MUST
     * adhere to the same constraints as member names with the additional requirement that they
     * MUST contain at least one non a-z character (U+0061 to U+007A)".
     *
     * @throws \WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized
     */
    public function validateQueryParams();

    /**
     * Returns a list of extensions by which the request is formatted.
     *
     * @return array
     */
    public function getExtensions();

    /**
     * Returns a list of extensions which are required by the request.
     *
     * @return array
     */
    public function getRequiredExtensions();

    /**
     * Returns a list of field names for the given resource type which are required to be present in the response.
     *
     * @param string $resourceType
     * @return array
     */
    public function getIncludedFields($resourceType);

    /**
     * Determines if a given field for a given resource type should be present in the response or not.
     *
     * @param string $resourceType
     * @param string $field
     * @return bool
     */
    public function isIncludedField($resourceType, $field);

    /**
     * Determines if the request needs any relationships to be included.
     *
     * @return bool
     */
    public function hasIncludedRelationships();

    /**
     * Returns a list of relationship paths for a given parent path.
     *
     * @param string $baseRelationshipPath
     * @return array
     */
    public function getIncludedRelationships($baseRelationshipPath);

    /**
     * Determines if a given relationship name that is a child of the $baseRelationshipPath is required to be included
     * in the response.
     *
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @param array $defaultRelationships
     * @return bool
     */
    public function isIncludedRelationship($baseRelationshipPath, $relationshipName, array $defaultRelationships);

    /**
     * @return array
     */
    public function getSorting();

    /**
     * @return array|null
     */
    public function getPagination();

    /**
     * @param mixed $defaultPage
     * @return \WoohooLabs\Yin\JsonApi\Request\Pagination\FixedPagePagination
     */
    public function getFixedPageBasedPagination($defaultPage = null);

    /**
     * @param mixed $defaultPage
     * @param mixed $defaultSize
     * @return \WoohooLabs\Yin\JsonApi\Request\Pagination\PagePagination
     */
    public function getPageBasedPagination($defaultPage = null, $defaultSize = null);

    /**
     * @param mixed $defaultOffset
     * @param mixed $defaultLimit
     * @return \WoohooLabs\Yin\JsonApi\Request\Pagination\OffsetPagination
     */
    public function getOffsetBasedPagination($defaultOffset = null, $defaultLimit = null);

    /**
     * @param mixed $defaultCursor
     * @return \WoohooLabs\Yin\JsonApi\Request\Pagination\CursorPagination
     */
    public function getCursorBasedPagination($defaultCursor = null);

    /**
     * @return array
     */
    public function getFiltering();

    /**
     * Returns a query parameter with a name of $name if it is present in the request, or the $default value otherwise.
     *
     * @param string $name
     * @param mixed $default
     * @return array|string
     */
    public function getQueryParam($name, $default = null);

    /**
     * Returns a query parameter with a name of $name if it is present in the request, or the $default value otherwise.
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function withQueryParam($name, $value);

    /**
     * Returns the "data" part of the request if it is present in the body, or null otherwise.
     *
     * @return array|null
     */
    public function getBodyData();

    /**
     * Returns the "type" key's value in the "data" part of the request if it is present in the body, or null otherwise.
     *
     * @return string|null
     */
    public function getBodyDataType();

    /**
     * Returns the "id" key's value in the "data" part of the request if it is present in the body, or null otherwise.
     *
     * @return string|null
     */
    public function getBodyDataId();
}
