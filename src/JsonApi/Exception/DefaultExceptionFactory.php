<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

class DefaultExceptionFactory implements ExceptionFactoryInterface
{
    public function createApplicationErrorException(JsonApiRequestInterface $request): JsonApiExceptionInterface
    {
        return new ApplicationError();
    }

    public function createClientGeneratedIdNotSupportedException(
        JsonApiRequestInterface $request,
        string $currentId
    ): JsonApiExceptionInterface {
        return new ClientGeneratedIdNotSupported($currentId);
    }

    public function createClientGeneratedIdAlreadyExistsException(
        JsonApiRequestInterface $request,
        string $currentId
    ): JsonApiExceptionInterface {
        return new ClientGeneratedIdAlreadyExists($currentId);
    }

    public function createClientGeneratedIdRequiredException(JsonApiRequestInterface $request): JsonApiExceptionInterface
    {
        return new ClientGeneratedIdRequired();
    }

    public function createFullReplacementProhibitedException(string $relationshipName): JsonApiExceptionInterface
    {
        return new FullReplacementProhibited($relationshipName);
    }

    public function createDataMemberMissingException(JsonApiRequestInterface $request): JsonApiExceptionInterface
    {
        return new DataMemberMissing();
    }

    public function createInclusionUnsupportedException(JsonApiRequestInterface $request): JsonApiExceptionInterface
    {
        return new InclusionUnsupported();
    }

    public function createInclusionUnrecognizedException(
        JsonApiRequestInterface $request,
        array $unrecognizedInclusions
    ): JsonApiExceptionInterface {
        return new InclusionUnrecognized($unrecognizedInclusions);
    }

    public function createMediaTypeUnacceptableException(
        JsonApiRequestInterface $request,
        string $mediaTypeName
    ): JsonApiExceptionInterface {
        return new MediaTypeUnacceptable($mediaTypeName);
    }

    public function createMediaTypeUnsupportedException(
        JsonApiRequestInterface $request,
        string $mediaTypeName
    ): JsonApiExceptionInterface {
        return new MediaTypeUnsupported($mediaTypeName);
    }

    public function createQueryParamUnrecognizedException(
        JsonApiRequestInterface $request,
        string $queryParamName
    ): JsonApiExceptionInterface {
        return new QueryParamUnrecognized($queryParamName);
    }

    public function createQueryParamMalformedException(
        JsonApiRequestInterface $request,
        string $queryParamName,
        $queryParamValue
    ): JsonApiExceptionInterface {
        return new QueryParamMalformed($queryParamName, $queryParamValue);
    }

    public function createRelationshipNotExistsException(string $relationship): JsonApiExceptionInterface
    {
        return new RelationshipNotExists($relationship);
    }

    public function createRelationshipTypeInappropriateException(
        string $relationshipName,
        string $currentRelationshipType,
        string $expectedRelationshipType
    ): JsonApiExceptionInterface {
        return new RelationshipTypeInappropriate(
            $relationshipName,
            $currentRelationshipType,
            $expectedRelationshipType
        );
    }

    public function createRemovalProhibitedException(string $relationshipName): JsonApiExceptionInterface
    {
        return new RemovalProhibited($relationshipName);
    }

    public function createRequestBodyInvalidJsonException(
        JsonApiRequestInterface $request,
        string $lintMessage,
        bool $includeOriginalBody
    ): JsonApiExceptionInterface {
        return new RequestBodyInvalidJson($request, $lintMessage, $includeOriginalBody);
    }

    public function createRequestBodyInvalidJsonApiException(
        JsonApiRequestInterface $request,
        array $validationErrors,
        bool $includeOriginalBody
    ): JsonApiExceptionInterface {
        return new RequestBodyInvalidJsonApi($request, $validationErrors, $includeOriginalBody);
    }

    public function createResourceIdentifierIdInvalidException(string $id): JsonApiExceptionInterface
    {
        return new ResourceIdentifierIdInvalid($id);
    }

    public function createResourceIdentifierIdMissingException(array $resourceIdentifier): JsonApiExceptionInterface
    {
        return new ResourceIdentifierIdMissing($resourceIdentifier);
    }

    public function createResourceIdentifierTypeInvalidException(string $type): JsonApiExceptionInterface
    {
        return new ResourceIdentifierTypeInvalid($type);
    }

    public function createResourceIdentifierTypeMissingException(array $resourceIdentifier): JsonApiExceptionInterface
    {
        return new ResourceIdentifierTypeMissing($resourceIdentifier);
    }

    public function createResourceIdInvalidException($id): JsonApiExceptionInterface
    {
        return new ResourceIdInvalid($id);
    }

    public function createResourceIdMissingException(): JsonApiExceptionInterface
    {
        return new ResourceIdMissing();
    }

    public function createResourceTypeMissingException(): JsonApiExceptionInterface
    {
        return new ResourceTypeMissing();
    }

    public function createResourceNotFoundException(JsonApiRequestInterface $request): JsonApiExceptionInterface
    {
        return new ResourceNotFound();
    }

    public function createResourceTypeUnacceptableException(
        $currentType,
        array $acceptedTypes
    ): JsonApiExceptionInterface {
        return new ResourceTypeUnacceptable($currentType, $acceptedTypes);
    }

    public function createResponseBodyInvalidJsonException(
        ResponseInterface $response,
        string $lintMessage,
        bool $includeOriginalBody
    ): JsonApiExceptionInterface {
        return new ResponseBodyInvalidJson($response, $lintMessage, $includeOriginalBody);
    }

    public function createResponseBodyInvalidJsonApiException(
        ResponseInterface $response,
        array $validationErrors,
        bool $includeOriginalBody
    ): JsonApiExceptionInterface {
        return new ResponseBodyInvalidJsonApi($response, $validationErrors, $includeOriginalBody);
    }

    public function createSortingUnsupportedException(JsonApiRequestInterface $request): JsonApiExceptionInterface
    {
        return new SortingUnsupported();
    }

    public function createSortParamUnrecognizedException(
        JsonApiRequestInterface $request,
        string $paramName
    ): JsonApiExceptionInterface {
        return new SortParamUnrecognized($paramName);
    }
}
