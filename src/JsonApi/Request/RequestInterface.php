<?php
namespace WoohooLabs\Yin\JsonApi\Request;

use Psr\Http\Message\ServerRequestInterface;

interface RequestInterface extends ServerRequestInterface
{
    /**
     * @throws \WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported
     */
    public function validateContentTypeHeader();

    /**
     * @throws \WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable
     */
    public function validateAcceptHeader();

    /**
     * @throws \WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized
     */
    public function validateQueryParams();

    /**
     * @return array
     */
    public function getExtensions();

    /**
     * @return array
     */
    public function getRequiredExtensions();

    /**
     * @param string $resourceType
     * @return array
     */
    public function getIncludedFields($resourceType);

    /**
     * @param string $resourceType
     * @param string $field
     * @return bool
     */
    public function isIncludedField($resourceType, $field);

    /**
     * @return bool
     */
    public function hasIncludedRelationships();

    /**
     * @param string $baseRelationshipPath
     * @return array
     */
    public function getIncludedRelationships($baseRelationshipPath);

    /**
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return bool
     */
    public function isIncludedRelationship($baseRelationshipPath, $relationshipName);

    /**
     * @return array
     */
    public function getSorting();

    /**
     * @return array|null
     */
    public function getPagination();

    /**
     * @return array
     */
    public function getFiltering();

    /**
     * @param string $name
     * @param mixed $default
     * @return array|string
     */
    public function getQueryParam($name, $default = null);

    /**
     * @return array|null
     */
    public function getBodyData();

    /**
     * @return string|null
     */
    public function getBodyDataType();

    /**
     * @return string|null
     */
    public function getBodyDataId();
}
