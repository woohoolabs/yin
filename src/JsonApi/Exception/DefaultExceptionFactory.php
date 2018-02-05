<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

class DefaultExceptionFactory implements ExceptionFactoryInterface
{
    public function createApplicationErrorException(RequestInterface $request): ApplicationError
    {
        return new ApplicationError();
    }

    public function createClientGeneratedIdNotSupportedException(
        RequestInterface $request,
        string $currentId
    ): ClientGeneratedIdNotSupported {
        return new ClientGeneratedIdNotSupported($currentId);
    }

    public function createClientGeneratedIdAlreadyExistsException(
        RequestInterface $request,
        string $currentId
    ): ClientGeneratedIdAlreadyExists {
        return new ClientGeneratedIdAlreadyExists($currentId);
    }

    public function createClientGeneratedIdRequiredException(RequestInterface $request): ClientGeneratedIdRequired
    {
        return new ClientGeneratedIdRequired();
    }

    public function createFullReplacementProhibitedException(string $relationshipName): FullReplacementProhibited
    {
        return new FullReplacementProhibited($relationshipName);
    }

    public function createDataMemberMissingException(RequestInterface $request): DataMemberMissing
    {
        return new DataMemberMissing();
    }

    public function createInclusionUnsupportedException(RequestInterface $request): InclusionUnsupported
    {
        return new InclusionUnsupported();
    }

    public function createInclusionUnrecognizedException(
        RequestInterface $request,
        array $unrecognizedInclusions
    ): InclusionUnrecognized {
        return new InclusionUnrecognized($unrecognizedInclusions);
    }

    public function createMediaTypeUnacceptableException(
        RequestInterface $request,
        string $mediaTypeName
    ): MediaTypeUnacceptable {
        return new MediaTypeUnacceptable($mediaTypeName);
    }

    public function createMediaTypeUnsupportedException(
        RequestInterface $request,
        string $mediaTypeName
    ): MediaTypeUnsupported {
        return new MediaTypeUnsupported($mediaTypeName);
    }

    public function createQueryParamUnrecognizedException(
        RequestInterface $request,
        string $queryParamName
    ): QueryParamUnrecognized {
        return new QueryParamUnrecognized($queryParamName);
    }

    public function createQueryParamMalformedException(
        RequestInterface $request,
        string $queryParamName,
        $queryParamValue
    ): QueryParamMalformed {
        return new QueryParamMalformed($queryParamName, $queryParamValue);
    }

    public function createRelationshipNotExistsException(string $relationship): RelationshipNotExists
    {
        return new RelationshipNotExists($relationship);
    }

    public function createRelationshipTypeInappropriateException(
        string $relationshipName,
        string $currentRelationshipType,
        string $expectedRelationshipType
    ) {
        return new RelationshipTypeInappropriate(
            $relationshipName,
            $currentRelationshipType,
            $expectedRelationshipType
        );
    }

    public function createRemovalProhibitedException(string $relationshipName): RemovalProhibited
    {
        return new RemovalProhibited($relationshipName);
    }

    public function createRequestBodyInvalidJsonException(
        RequestInterface $request,
        string $lintMessage,
        bool $includeOriginalBody
    ): RequestBodyInvalidJson {
        return new RequestBodyInvalidJson($request, $lintMessage, $includeOriginalBody);
    }

    public function createRequestBodyInvalidJsonApiException(
        RequestInterface $request,
        array $validationErrors,
        bool $includeOriginalBody
    ): RequestBodyInvalidJsonApi {
        return new RequestBodyInvalidJsonApi($request, $validationErrors, $includeOriginalBody);
    }

    public function createResourceIdentifierIdInvalidException($id): ResourceIdentifierIdInvalid
    {
        return new ResourceIdentifierIdInvalid($id);
    }

    public function createResourceIdentifierIdMissingException(array $resourceIdentifier): ResourceIdentifierIdMissing
    {
        return new ResourceIdentifierIdMissing($resourceIdentifier);
    }

    public function createResourceIdentifierTypeInvalidException($type): ResourceIdentifierTypeInvalid
    {
        return new ResourceIdentifierTypeInvalid($type);
    }

    public function createResourceIdentifierTypeMissingException(array $resourceIdentifier): ResourceIdentifierTypeMissing
    {
        return new ResourceIdentifierTypeMissing($resourceIdentifier);
    }

    public function createResourceIdInvalidException($id): ResourceIdInvalid
    {
        return new ResourceIdInvalid($id);
    }

    public function createResourceIdMissingException(): ResourceIdMissing
    {
        return new ResourceIdMissing();
    }

    public function createResourceTypeMissingException(): ResourceTypeMissing
    {
        return new ResourceTypeMissing();
    }

    public function createResourceNotFoundException(RequestInterface $request): ResourceNotFound
    {
        return new ResourceNotFound();
    }

    public function createResourceTypeUnacceptableException(
        $currentType,
        array $acceptedTypes
    ): ResourceTypeUnacceptable {
        return new ResourceTypeUnacceptable($currentType, $acceptedTypes);
    }

    public function createResponseBodyInvalidJsonException(
        ResponseInterface $response,
        string $lintMessage,
        bool $includeOriginalBody
    ): ResponseBodyInvalidJson {
        return new ResponseBodyInvalidJson($response, $lintMessage, $includeOriginalBody);
    }

    public function createResponseBodyInvalidJsonApiException(
        ResponseInterface $response,
        array $validationErrors,
        bool $includeOriginalBody
    ): ResponseBodyInvalidJsonApi {
        return new ResponseBodyInvalidJsonApi($response, $validationErrors, $includeOriginalBody);
    }

    public function createSortingUnsupportedException(RequestInterface $request): SortingUnsupported
    {
        return new SortingUnsupported();
    }

    public function createSortParamUnrecognizedException(
        RequestInterface $request,
        string $paramName
    ): SortParamUnrecognized {
        return new SortParamUnrecognized($paramName);
    }
}
