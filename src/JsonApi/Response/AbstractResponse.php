<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument;

abstract class AbstractResponse
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public static function genericSuccess(ResponseInterface $response, $statusCode)
    {
        return $response->withStatus($statusCode);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $statusCode
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public static function genericError(
        ResponseInterface $response,
        $statusCode,
        AbstractErrorDocument $document,
        array $errors = []
    ) {
        return self::getErrorResponse($response, $document, $errors, $statusCode);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected static function getDocumentResourceResponse(
        RequestInterface $request,
        ResponseInterface $response,
        AbstractCompoundDocument $document,
        $resource,
        $statusCode
    ) {
        return $document->getResponse($response, $resource, $request, $statusCode);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected static function getDocumentMetaResponse(
        RequestInterface $request,
        ResponseInterface $response,
        AbstractCompoundDocument $document,
        $resource,
        $statusCode
    ) {
        return $document->getMetaResponse($response, $resource, $request, $statusCode);
    }

    /**
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected static function getDocumentRelationshipResponse(
        $relationshipName,
        RequestInterface $request,
        ResponseInterface $response,
        AbstractCompoundDocument $document,
        $resource,
        $statusCode
    ) {
        return $document->getRelationshipResponse($relationshipName, $response, $resource, $request, $statusCode);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    protected static function getErrorResponse(
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
