<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use Exception;
use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

interface ExceptionFactoryInterface
{
    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createApplicationErrorException(RequestInterface $request);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createClientGeneratedIdNotSupportedException(RequestInterface $request, string $currentId);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createClientGeneratedIdAlreadyExistsException(RequestInterface $request, string $currentId);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createClientGeneratedIdRequiredException(RequestInterface $request);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createDataMemberMissingException(RequestInterface $request);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createFullReplacementProhibitedException(string $relationshipName);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createInclusionUnsupportedException(RequestInterface $request);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createInclusionUnrecognizedException(RequestInterface $request, array $unrecognizedInclusions);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createMediaTypeUnsupportedException(RequestInterface $request, string $mediaTypeName);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createMediaTypeUnacceptableException(RequestInterface $request, string $mediaTypeName);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createQueryParamUnrecognizedException(RequestInterface $request, string $queryParamName);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createQueryParamMalformedException(RequestInterface $request, string $queryParamName, $queryParamValue);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createRelationshipNotExistsException(string $relationship);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createRelationshipTypeInappropriateException(
        string $relationshipName,
        string $currentRelationshipType,
        string $expectedRelationshipType
    );

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createRemovalProhibitedException(string $relationshipName);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createRequestBodyInvalidJsonException(
        RequestInterface $request,
        string $lintMessage,
        bool $includeOriginalBody
    );

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createRequestBodyInvalidJsonApiException(
        RequestInterface $request,
        array $validationErrors,
        bool $includeOriginalBody
    );

    /**
     * @param mixed $id
     * @return JsonApiExceptionInterface|Exception
     */
    public function createResourceIdentifierIdInvalidException($id);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createResourceIdentifierIdMissingException(array $resourceIdentifier);

    /**
     * @param mixed $type
     * @return JsonApiExceptionInterface|Exception
     */
    public function createResourceIdentifierTypeInvalidException($type);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createResourceIdentifierTypeMissingException(array $resourceIdentifier);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createResourceIdInvalidException($id);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createResourceIdMissingException();

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createResourceNotFoundException(RequestInterface $request);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createResourceTypeMissingException();

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createResourceTypeUnacceptableException($currentType, array $acceptedTypes);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createResponseBodyInvalidJsonException(
        ResponseInterface $response,
        string $lintMessage,
        bool $includeOriginalBody
    );

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createResponseBodyInvalidJsonApiException(
        ResponseInterface $response,
        array $validationErrors,
        bool $includeOriginalBody
    );

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createSortingUnsupportedException(RequestInterface $request);

    /**
     * @return JsonApiExceptionInterface|Exception
     */
    public function createSortParamUnrecognizedException(RequestInterface $request, string $paramName);
}
