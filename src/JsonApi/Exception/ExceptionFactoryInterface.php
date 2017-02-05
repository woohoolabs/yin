<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use Exception;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

interface ExceptionFactoryInterface
{
    /**
     * @return Exception
     */
    public function createApplicationErrorException(RequestInterface $request);

    /**
     * @return Exception
     */
    public function createClientGeneratedIdNotSupportedException(RequestInterface $request, string $currentId);

    /**
     * @return Exception
     */
    public function createClientGeneratedIdAlreadyExistsException(RequestInterface $request, string $currentId);

    /**
     * @return Exception
     */
    public function createClientGeneratedIdRequiredException(RequestInterface $request);

    /**
     * @return Exception
     */
    public function createDataMemberMissingException(RequestInterface $request);

    /**
     * @return Exception
     */
    public function createFullReplacementProhibitedException(string $relationshipName);

    /**
     * @return Exception
     */
    public function createInclusionUnsupportedException(RequestInterface $request);

    /**
     * @return Exception
     */
    public function createInclusionUnrecognizedException(RequestInterface $request, array $unrecognizedInclusions);

    /**
     * @return Exception
     */
    public function createMediaTypeUnsupportedException(RequestInterface $request, string $mediaTypeName);

    /**
     * @return Exception
     */
    public function createMediaTypeUnacceptableException(RequestInterface $request, string $mediaTypeName);

    /**
     * @return Exception
     */
    public function createQueryParamUnrecognizedException(RequestInterface $request, string $queryParamName);

    /**
     * @return Exception
     */
    public function createRelationshipNotExists(string $relationship);

    /**
     * @return Exception
     */
    public function createRelationshipTypeInappropriateException(
        string $relationshipName,
        string $currentRelationshipType,
        string $expectedRelationshipType
    );

    /**
     * @return Exception
     */
    public function createRemovalProhibitedException(string $relationshipName);

    /**
     * @return Exception
     */
    public function createRequestBodyInvalidJsonException(
        RequestInterface $request,
        string $lintMessage,
        bool $includeOriginalBody
    );

    /**
     * @return Exception
     */
    public function createRequestBodyInvalidJsonApiException(
        RequestInterface $request,
        array $validationErrors,
        bool $includeOriginalBody
    );

    /**
     * @return Exception
     */
    public function createResourceIdentifierIdMissing(array $resourceIdentifier);

    /**
     * @return Exception
     */
    public function createResourceIdentifierTypeMissing(array $resourceIdentifier);

    /**
     * @return Exception
     */
    public function createResourceIdInvalidException(string $id);

    /**
     * @return Exception
     */
    public function createResourceIdMissingException();

    /**
     * @return Exception
     */
    public function createResourceNotFoundException(RequestInterface $request);

    /**
     * @return Exception
     */
    public function createResourceTypeMissingException();

    /**
     * @return Exception
     */
    public function createResourceTypeUnacceptableException(string $currentType, array $acceptedTypes);

    /**
     * @return Exception
     */
    public function createResponseBodyInvalidJsonException(
        ResponseInterface $response,
        string $lintMessage,
        bool $includeOriginalBody
    );

    /**
     * @return Exception
     */
    public function createResponseBodyInvalidJsonApiException(
        ResponseInterface $response,
        array $validationErrors,
        bool $includeOriginalBody
    );

    /**
     * @return Exception
     */
    public function createSortingUnsupportedException(RequestInterface $request);

    /**
     * @return Exception
     */
    public function createSortParamUnrecognizedException(RequestInterface $request, string $paramName);
}
