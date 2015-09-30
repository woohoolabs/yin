<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

interface ExceptionFactoryInterface
{
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
     * @param string $relationshipName
     * @return \Exception
     */
    public function createFullReplacementProhibitedException($relationshipName);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return \WoohooLabs\Yin\JsonApi\Exception\JsonApiException
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
     * @return \Exception
     */
    public function createResourceIdMissingException();

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
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return \Exception
     */
    public function createSortingUnsupportedException(RequestInterface $request);
}
