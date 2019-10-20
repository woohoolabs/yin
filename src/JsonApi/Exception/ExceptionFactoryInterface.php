<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

interface ExceptionFactoryInterface
{
    public function createApplicationErrorException(JsonApiRequestInterface $request): JsonApiExceptionInterface;

    public function createClientGeneratedIdNotSupportedException(
        JsonApiRequestInterface $request,
        string $currentId
    ): JsonApiExceptionInterface;

    public function createClientGeneratedIdAlreadyExistsException(JsonApiRequestInterface $request, string $currentId): JsonApiExceptionInterface;

    public function createClientGeneratedIdRequiredException(JsonApiRequestInterface $request): JsonApiExceptionInterface;

    public function createDataMemberMissingException(JsonApiRequestInterface $request): JsonApiExceptionInterface;

    public function createFullReplacementProhibitedException(string $relationshipName): JsonApiExceptionInterface;

    public function createInclusionUnsupportedException(JsonApiRequestInterface $request): JsonApiExceptionInterface;

    public function createInclusionUnrecognizedException(
        JsonApiRequestInterface $request,
        array $unrecognizedInclusions
    ): JsonApiExceptionInterface;

    public function createMediaTypeUnsupportedException(
        JsonApiRequestInterface $request,
        string $mediaTypeName
    ): JsonApiExceptionInterface;

    public function createMediaTypeUnacceptableException(
        JsonApiRequestInterface $request,
        string $mediaTypeName
    ): JsonApiExceptionInterface;

    public function createQueryParamUnrecognizedException(
        JsonApiRequestInterface $request,
        string $queryParamName
    ): JsonApiExceptionInterface;

    /**
     * @param mixed $queryParamValue
     */
    public function createQueryParamMalformedException(
        JsonApiRequestInterface $request,
        string $queryParamName,
        $queryParamValue
    ): JsonApiExceptionInterface;

    public function createRelationshipNotExistsException(string $relationship): JsonApiExceptionInterface;

    public function createRelationshipTypeInappropriateException(
        string $relationshipName,
        string $currentRelationshipType,
        string $expectedRelationshipType
    ): JsonApiExceptionInterface;

    public function createRemovalProhibitedException(string $relationshipName): JsonApiExceptionInterface;

    public function createRequestBodyInvalidJsonException(
        JsonApiRequestInterface $request,
        string $lintMessage,
        bool $includeOriginalBody
    ): JsonApiExceptionInterface;

    public function createRequestBodyInvalidJsonApiException(
        JsonApiRequestInterface $request,
        array $validationErrors,
        bool $includeOriginalBody
    ): JsonApiExceptionInterface;

    public function createResourceIdentifierIdInvalidException(string $type): JsonApiExceptionInterface;

    public function createResourceIdentifierIdMissingException(array $resourceIdentifier): JsonApiExceptionInterface;

    public function createResourceIdentifierTypeInvalidException(string $type): JsonApiExceptionInterface;

    public function createResourceIdentifierTypeMissingException(array $resourceIdentifier): JsonApiExceptionInterface;

    /**
     * @param mixed $id
     */
    public function createResourceIdInvalidException($id): JsonApiExceptionInterface;

    public function createResourceIdMissingException(): JsonApiExceptionInterface;

    public function createResourceNotFoundException(JsonApiRequestInterface $request): JsonApiExceptionInterface;

    public function createResourceTypeMissingException(): JsonApiExceptionInterface;

    /**
     * @param mixed $currentType
     */
    public function createResourceTypeUnacceptableException($currentType, array $acceptedTypes): JsonApiExceptionInterface;

    public function createResponseBodyInvalidJsonException(
        ResponseInterface $response,
        string $lintMessage,
        bool $includeOriginalBody
    ): JsonApiExceptionInterface;

    public function createResponseBodyInvalidJsonApiException(
        ResponseInterface $response,
        array $validationErrors,
        bool $includeOriginalBody
    ): JsonApiExceptionInterface;

    public function createSortingUnsupportedException(JsonApiRequestInterface $request): JsonApiExceptionInterface;

    public function createSortParamUnrecognizedException(JsonApiRequestInterface $request, string $paramName): JsonApiExceptionInterface;
}
