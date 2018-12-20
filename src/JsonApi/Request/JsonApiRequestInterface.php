<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request;

use Psr\Http\Message\ServerRequestInterface;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable;
use WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;

interface JsonApiRequestInterface extends ServerRequestInterface
{
    /**
     * Validates if the current request's "Content-Type" header conforms to the JSON:API schema.
     * @throws MediaTypeUnsupported|JsonApiExceptionInterface
     */
    public function validateContentTypeHeader(): void;

    /**
     * Validates if the current request's "Accept" header conforms to the JSON:API schema.
     * @throws MediaTypeUnacceptable|JsonApiExceptionInterface
     */
    public function validateAcceptHeader(): void;

    /**
     * Validates if the current request's query parameters conform to the JSON:API schema.
     *
     * According to the JSON:API specification "Implementation specific query parameters MUST
     * adhere to the same constraints as member names with the additional requirement that they
     * MUST contain at least one non a-z character (U+0061 to U+007A)".
     * @throws QueryParamUnrecognized|JsonApiExceptionInterface
     */
    public function validateQueryParams(): void;

    /**
     * Returns a list of field names for the given resource type which should be present in the response.
     */
    public function getIncludedFields(string $resourceType): array;

    /**
     * Determines if a given field for a given resource type should be present in the response or not.
     */
    public function isIncludedField(string $resourceType, string $field): bool;

    /**
     * Determines if any relationship needs to be included.
     */
    public function hasIncludedRelationships(): bool;

    /**
     * Returns a list of relationship paths for a given parent path which should be included in the response.
     */
    public function getIncludedRelationships(string $baseRelationshipPath): array;

    /**
     * Determines if a given relationship name that is a child of the $baseRelationshipPath should be included
     * in the response.
     */
    public function isIncludedRelationship(string $baseRelationshipPath, string $relationshipName, array $defaultRelationships): bool;

    /**
     * Returns the "sort[]" query parameters.
     */
    public function getSorting(): array;

    /**
     * Returns the "page[]" query parameters.
     */
    public function getPagination(): array;

    /**
     * Returns the "filter[]" query parameters.
     */
    public function getFiltering(): array;

    /**
     * Returns the value of the "filter[$param]" query parameter if present or $default value otherwise
     * @param mixed|null $default
     * @return string|mixed
     */
    public function getFilteringParam(string $param, $default = null);

    /**
     * Returns the value of the "$name" query parameter if present or the $default value otherwise.
     * @param mixed $default
     * @return array|string|mixed
     */
    public function getQueryParam(string $name, $default = null);

    /**
     * Returns a new request with the "$name" query parameter.
     * @param mixed $value
     * @return $this
     */
    public function withQueryParam(string $name, $value);

    /**
     * @return string[]
     */
    public function getAppliedProfiles(): array;

    public function isProfileApplied(string $profile): bool;

    /**
     * @return string[]
     */
    public function getRequiredProfiles(): array;

    public function isProfileRequired(string $profile): bool;

    /**
     * @return string[]
     */
    public function getRequestedProfiles(): array;

    public function isProfileRequested(string $profile): bool;

    /**
     * Returns the primary resource if it is present in the request body, or the $default value otherwise.
     * @param mixed $default
     * @return array|mixed
     */
    public function getResource($default = null);

    /**
     * Returns the "type" of the primary resource if it is present, or the $default value otherwise.
     * @param mixed $default
     * @return string|mixed
     */
    public function getResourceType($default = null);

    /**
     * Returns the "id" of the primary resource if it is present, or the $default value otherwise.
     * @param mixed $default
     * @return string|mixed
     */
    public function getResourceId($default = null);

    /**
     * Returns the "attributes" of the primary resource.
     */
    public function getResourceAttributes(): array;

    /**
     * Returns the $attribute attribute of the primary resource if it is present, or the $default value otherwise.
     * @param mixed $default
     * @return mixed
     */
    public function getResourceAttribute(string $attribute, $default = null);

    /**
     * Returns if the $relationship to-one relationship of the primary resource is present.
     */
    public function hasToOneRelationship(string $relationship): bool;

    /**
     * Returns the $relationship to-one relationship of the primary resource if it is present, or null otherwise.
     */
    public function getToOneRelationship(string $relationship): ToOneRelationship;

    /**
     * Returns if the $relationship to-many relationship of the primary resource is present.
     */
    public function hasToManyRelationship(string $relationship): bool;

    /**
     * Returns the $relationship to-many relationship of the primary resource if it is present, or null otherwise.
     */
    public function getToManyRelationship(string $relationship): ToManyRelationship;
}
