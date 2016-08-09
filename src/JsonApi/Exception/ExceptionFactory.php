<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

class ExceptionFactory implements ExceptionFactoryInterface
{
    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\ApplicationError
     */
    public function createApplicationErrorException(RequestInterface $request)
    {
        return new ApplicationError();
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdNotSupported
     */
    public function createClientGeneratedIdNotSupportedException(RequestInterface $request, $currentId)
    {
        return new ClientGeneratedIdNotSupported($currentId);
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdAlreadyExists
     */
    public function createClientGeneratedIdAlreadyExistsException(RequestInterface $request, $currentId)
    {
        return new ClientGeneratedIdAlreadyExists($currentId);
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdRequired
     */
    public function createClientGeneratedIdRequiredException(RequestInterface $request)
    {
        return new ClientGeneratedIdRequired();
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\FullReplacementProhibited
     */
    public function createFullReplacementProhibitedException($relationshipName)
    {
        return new FullReplacementProhibited($relationshipName);
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\DataMemberMissing
     */
    public function createDataMemberMissingException(RequestInterface $request)
    {
        return new DataMemberMissing();
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\InclusionUnsupported
     */
    public function createInclusionUnsupportedException(RequestInterface $request)
    {
        return new InclusionUnsupported();
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\InclusionUnrecognized
     */
    public function createInclusionUnrecognizedException(RequestInterface $request, array $unrecognizedInclusions)
    {
        return new InclusionUnrecognized($unrecognizedInclusions);
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnacceptable
     */
    public function createMediaTypeUnacceptableException(RequestInterface $request, $mediaTypeName)
    {
        return new MediaTypeUnacceptable($mediaTypeName);
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\MediaTypeUnsupported
     */
    public function createMediaTypeUnsupportedException(RequestInterface $request, $mediaTypeName)
    {
        return new MediaTypeUnsupported($mediaTypeName);
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized
     */
    public function createQueryParamUnrecognizedException(RequestInterface $request, $queryParamName)
    {
        return new QueryParamUnrecognized($queryParamName);
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\RelationshipTypeInappropriate
     */
    public function createRelationshipTypeInappropriateException(
        $relationshipName,
        $currentRelationshipType,
        $expectedRelationshipType
    ) {
        return new RelationshipTypeInappropriate(
            $relationshipName,
            $currentRelationshipType,
            $expectedRelationshipType
        );
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\RemovalProhibited
     */
    public function createRemovalProhibitedException($relationshipName)
    {
        return new RemovalProhibited($relationshipName);
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\RequestBodyInvalidJson
     */
    public function createRequestBodyInvalidJsonException(
        RequestInterface $request,
        $lintMessage,
        $includeOriginalBody
    ) {
        return new RequestBodyInvalidJson($request, $lintMessage, $includeOriginalBody);
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\RequestBodyInvalidJsonApi
     */
    public function createRequestBodyInvalidJsonApiException(
        RequestInterface $request,
        array $validationErrors,
        $includeOriginalBody
    ) {
        return new RequestBodyInvalidJsonApi($request, $validationErrors, $includeOriginalBody);
    }

    /**
     * @param array $resourceIdentifier
     * @return \Exception
     */
    public function createResourceIdentifierIdMissing(array $resourceIdentifier)
    {
        return new ResourceIdentifierIdMissing($resourceIdentifier);
    }

    /**
     * @param array $resourceIdentifier
     * @return \Exception
     */
    public function createResourceIdentifierTypeMissing(array $resourceIdentifier)
    {
        return new ResourceIdentifierTypeMissing($resourceIdentifier);
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\ResourceIdInvalid
     */
    public function createResourceIdInvalidException($id)
    {
        return new ResourceIdInvalid($id);
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\ResourceIdMissing
     */
    public function createResourceIdMissingException()
    {
        return new ResourceIdMissing();
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeMissing
     */
    public function createResourceTypeMissingException()
    {
        return new ResourceTypeMissing();
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\ResourceNotFound
     */
    public function createResourceNotFoundException(RequestInterface $request)
    {
        return new ResourceNotFound();
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\ResourceTypeUnacceptable
     */
    public function createResourceTypeUnacceptableException($currentType, array $acceptedTypes)
    {
        return new ResourceTypeUnacceptable($currentType, $acceptedTypes);
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\ResponseBodyInvalidJson
     */
    public function createResponseBodyInvalidJsonException(
        ResponseInterface $response,
        $lintMessage,
        $includeOriginalBody
    ) {
        return new ResponseBodyInvalidJson($response, $lintMessage, $includeOriginalBody);
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\ResponseBodyInvalidJsonApi
     */
    public function createResponseBodyInvalidJsonApiException(
        ResponseInterface $response,
        array $validationErrors,
        $includeOriginalBody
    ) {
        return new ResponseBodyInvalidJsonApi($response, $validationErrors, $includeOriginalBody);
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\SortingUnsupported
     */
    public function createSortingUnsupportedException(RequestInterface $request)
    {
        return new SortingUnsupported();
    }

    /**
     * @inheritDoc
     * @return \WoohooLabs\Yin\JsonApi\Exception\SortParamUnrecognized
     */
    public function createSortParamUnrecognizedException(RequestInterface $request, $paramName)
    {
        return new SortParamUnrecognized($paramName);
    }
}
