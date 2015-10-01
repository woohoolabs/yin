<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument;
use WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

abstract class AbstractResponder
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Request\RequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument $document
     * @param mixed $domainObject
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getDocumentResourceResponse(
        RequestInterface $request,
        ResponseInterface $response,
        AbstractSuccessfulDocument $document,
        $domainObject,
        $statusCode
    ) {
        return $document->getResponse($response, $domainObject, $request, $statusCode);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument $document
     * @param mixed $domainObject
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getDocumentMetaResponse(
        ResponseInterface $response,
        AbstractSuccessfulDocument $document,
        $domainObject,
        $statusCode
    ) {
        return $document->getMetaResponse($response, $domainObject, $statusCode);
    }

    /**
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument $document
     * @param mixed $domainObject
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getDocumentRelationshipResponse(
        $relationshipName,
        RequestInterface $request,
        ResponseInterface $response,
        AbstractSuccessfulDocument $document,
        $domainObject,
        $statusCode
    ) {
        return $document->getRelationshipResponse($relationshipName, $response, $domainObject, $request, $statusCode);
    }

    /**
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument $document
     * @param mixed $domainObject
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getDocumentRelationshipMetaResponse(
        $relationshipName,
        RequestInterface $request,
        ResponseInterface $response,
        AbstractSuccessfulDocument $document,
        $domainObject,
        $statusCode
    ) {
        return $document->getRelationshipResponse($relationshipName, $response, $domainObject, $request, $statusCode);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument $document
     * @param \WoohooLabs\Yin\JsonApi\Schema\Error[] $errors
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    protected function getErrorResponse(
        ResponseInterface $response,
        AbstractErrorDocument $document,
        array $errors,
        $statusCode
    ) {
        foreach ($errors as $error) {
            $document->addError($error);
        }

        return $document->getResponse($response, $statusCode);
    }
}
