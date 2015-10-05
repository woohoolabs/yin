<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

class ExceptionFactory implements ExceptionFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createApplicationErrorException(RequestInterface $request)
    {
        return new ApplicationError();
    }

    /**
     * @inheritDoc
     */
    public function createClientGeneratedIdNotSupportedException(RequestInterface $request, $currentId)
    {
        return new ClientGeneratedIdNotSupported($currentId);
    }

    /**
     * @inheritDoc
     */
    public function createClientGeneratedIdAlreadyExistsException(RequestInterface $request, $currentId)
    {
        return new ClientGeneratedIdAlreadyExists($currentId);
    }

    /**
     * @inheritDoc
     */
    public function createFullReplacementProhibitedException($relationshipName)
    {
        return new FullReplacementProhibited($relationshipName);
    }

    /**
     * @inheritDoc
     */
    public function createDataMemberMissingException(RequestInterface $request)
    {
        return new DataMemberMissing();
    }

    /**
     * @inheritDoc
     */
    public function createInclusionUnsupportedException(RequestInterface $request)
    {
        return new InclusionUnsupported();
    }

    /**
     * @inheritDoc
     */
    public function createInclusionUnrecognizedException(RequestInterface $request, array $unrecognizedInclusions)
    {
        return new InclusionUnrecognized($unrecognizedInclusions);
    }

    /**
     * @inheritDoc
     */
    public function createMediaTypeUnacceptableException(RequestInterface $request, $mediaTypeName)
    {
        return new MediaTypeUnacceptable($mediaTypeName);
    }

    /**
     * @inheritDoc
     */
    public function createMediaTypeUnsupportedException(RequestInterface $request, $mediaTypeName)
    {
        return new MediaTypeUnsupported($mediaTypeName);
    }

    /**
     * @inheritDoc
     */
    public function createQueryParamUnrecognizedException(RequestInterface $request, $queryParamName)
    {
        return new QueryParamUnrecognized($queryParamName);
    }

    /**
     * @inheritDoc
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
     */
    public function createRemovalProhibitedException($relationshipName)
    {
        return new RemovalProhibited($relationshipName);
    }

    /**
     * @inheritDoc
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
     */
    public function createRequestBodyInvalidJsonApiException(
        RequestInterface $request,
        array $validationErrors,
        $includeOriginalBody
    ) {
        return new RequestBodyInvalidJsonApi($request, $validationErrors, $includeOriginalBody);
    }

    /**
     * @inheritDoc
     */
    public function createResourceIdInvalidException($id)
    {
        return new ResourceIdInvalid($id);
    }

    /**
     * @inheritDoc
     */
    public function createResourceIdMissingException()
    {
        return new ResourceIdMissing();
    }

    /**
     * @inheritDoc
     */
    public function createResourceTypeMissingException()
    {
        return new ResourceTypeMissing();
    }

    /**
     * @inheritDoc
     */
    public function createResourceNotFoundException(RequestInterface $request)
    {
        return new ResourceNotFound();
    }

    /**
     * @inheritDoc
     */
    public function createResourceTypeUnacceptableException($currentType, array $acceptedTypes)
    {
        return new ResourceTypeUnacceptable($currentType, $acceptedTypes);
    }

    /**
     * @inheritDoc
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
     */
    public function createSortingUnsupportedException(RequestInterface $request)
    {
        return new SortingUnsupported();
    }

    /**
     * @inheritDoc
     */
    public function createSortParamUnrecognizedException(RequestInterface $request, $paramName)
    {
        return new SortParamUnrecognized($paramName);
    }
}
