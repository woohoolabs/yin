<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

interface ExceptionFactoryInterface
{
    public function createApplicationErrorException(RequestInterface $request): JsonApiExceptionInterface;

    public function createClientGeneratedIdNotSupportedException(
        RequestInterface $request,
        string $currentId
    ): JsonApiExceptionInterface;

    public function createClientGeneratedIdAlreadyExistsException(RequestInterface $request, string $currentId): JsonApiExceptionInterface;

    public function createClientGeneratedIdRequiredException(RequestInterface $request): JsonApiExceptionInterface;

    public function createDataMemberMissingException(RequestInterface $request): JsonApiExceptionInterface;

    public function createFullReplacementProhibitedException(string $relationshipName): JsonApiExceptionInterface;

    public function createInclusionUnsupportedException(RequestInterface $request): JsonApiExceptionInterface;

    public function createInclusionUnrecognizedException(
        RequestInterface $request,
        array $unrecognizedInclusions
    ): JsonApiExceptionInterface;

    public function createMediaTypeUnsupportedException(
        RequestInterface $request,
        string $mediaTypeName
    ): JsonApiExceptionInterface;

    public function createMediaTypeUnacceptableException(
        RequestInterface $request,
        string $mediaTypeName
    ): JsonApiExceptionInterface;

    public function createQueryParamUnrecognizedException(
        RequestInterface $request,
        string $queryParamName
    ): JsonApiExceptionInterface;

    public function createQueryParamMalformedException(
        RequestInterface $request,
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
        RequestInterface $request,
        string $lintMessage,
        bool $includeOriginalBody
    ): JsonApiExceptionInterface;

    public function createRequestBodyInvalidJsonApiException(
        RequestInterface $request,
        array $validationErrors,
        bool $includeOriginalBody
    ): JsonApiExceptionInterface;

    public function createResourceIdentifierIdInvalidException(string $id): JsonApiExceptionInterface;

    public function createResourceIdentifierIdMissingException(array $resourceIdentifier): JsonApiExceptionInterface;

    public function createResourceIdentifierTypeInvalidException(string $type): JsonApiExceptionInterface;

    public function createResourceIdentifierTypeMissingException(array $resourceIdentifier): JsonApiExceptionInterface;

    public function createResourceIdInvalidException($id): JsonApiExceptionInterface;

    public function createResourceIdMissingException(): JsonApiExceptionInterface;

    public function createResourceNotFoundException(RequestInterface $request): JsonApiExceptionInterface;

    public function createResourceTypeMissingException(): JsonApiExceptionInterface;

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

    public function createSortingUnsupportedException(RequestInterface $request): JsonApiExceptionInterface;

    public function createSortParamUnrecognizedException(RequestInterface $request, string $paramName): JsonApiExceptionInterface;
}
