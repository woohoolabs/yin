<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

interface ExceptionFactoryInterface
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return \Exception
     */
    public function createApplicationErrorException(RequestInterface $request);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param string $currentId
     * @return \Exception
     */
    public function createClientGeneratedIdNotSupportedException(RequestInterface $request, $currentId);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param string $currentId
     * @return \Exception
     */
    public function createClientGeneratedIdAlreadyExistsException(RequestInterface $request, $currentId);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return \Exception
     */
    public function createClientGeneratedIdRequiredException(RequestInterface $request);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return \Exception
     */
    public function createDataMemberMissingException(RequestInterface $request);

    /**
     * @param string $relationshipName
     * @return \Exception
     */
    public function createFullReplacementProhibitedException($relationshipName);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return \Exception
     */
    public function createInclusionUnsupportedException(RequestInterface $request);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param array $unrecognizedInclusions
     * @return \Exception
     */
    public function createInclusionUnrecognizedException(RequestInterface $request, array $unrecognizedInclusions);


    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param string $mediaTypeName
     * @return \Exception
     */
    public function createMediaTypeUnsupportedException(RequestInterface $request, $mediaTypeName);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param string $mediaTypeName
     * @return \Exception
     */
    public function createMediaTypeUnacceptableException(RequestInterface $request, $mediaTypeName);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param string $queryParamName
     * @return \Exception
     */
    public function createQueryParamUnrecognizedException(RequestInterface $request, $queryParamName);

    /**
     * @param string $relationshipName
     * @param string $currentRelationshipType
     * @param string $expectedRelationshipType
     * @return \Exception
     */
    public function createRelationshipTypeInappropriateException(
        $relationshipName,
        $currentRelationshipType,
        $expectedRelationshipType
    );

    /**
     * @param string $relationshipName
     * @return \Exception
     */
    public function createRemovalProhibitedException($relationshipName);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param string $lintMessage
     * @param boolean $includeOriginalBody
     */
    public function createRequestBodyInvalidJsonException(
        RequestInterface $request,
        $lintMessage,
        $includeOriginalBody
    );

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param array $validationErrors
     * @param boolean $includeOriginalBody
     */
    public function createRequestBodyInvalidJsonApiException(
        RequestInterface $request,
        array $validationErrors,
        $includeOriginalBody
    );

    /**
     * @param array $resourceIdentifier
     * @return \Exception
     */
    public function createResourceIdentifierIdMissing(array $resourceIdentifier);

    /**
     * @param array $resourceIdentifier
     * @return \Exception
     */
    public function createResourceIdentifierTypeMissing(array $resourceIdentifier);

    /**
     * @param string $id
     * @return \Exception
     */
    public function createResourceIdInvalidException($id);

    /**
     * @return \Exception
     */
    public function createResourceIdMissingException();

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return \Exception
     */
    public function createResourceNotFoundException(RequestInterface $request);

    /**
     * @return \Exception
     */
    public function createResourceTypeMissingException();

    /**
     * @param string $currentType
     * @param array $acceptedTypes
     * @return \Exception
     */
    public function createResourceTypeUnacceptableException($currentType, array $acceptedTypes);

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string $lintMessage
     * @param boolean $includeOriginalBody
     * @return \Exception
     */
    public function createResponseBodyInvalidJsonException(
        ResponseInterface $response,
        $lintMessage,
        $includeOriginalBody
    );

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $validationErrors
     * @param boolean $includeOriginalBody
     * @return \Exception
     */
    public function createResponseBodyInvalidJsonApiException(
        ResponseInterface $response,
        array $validationErrors,
        $includeOriginalBody
    );

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return \Exception
     */
    public function createSortingUnsupportedException(RequestInterface $request);

    /**
     * @param string $paramName
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return \Exception
     */
    public function createSortParamUnrecognizedException(RequestInterface $request, $paramName);
}
