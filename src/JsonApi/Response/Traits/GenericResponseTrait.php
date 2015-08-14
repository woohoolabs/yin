<?php
namespace WoohooLabs\Yin\JsonApi\Response\Traits;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

trait GenericResponseTrait
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
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function genericSuccess($statusCode)
    {
        return self::getGenericSuccess($this->response, $statusCode);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public static function getGenericSuccess(ResponseInterface $response, $statusCode)
    {
        return $response->withStatus($statusCode);
    }

    /**
     * @param int $statusCode
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function genericError($statusCode, AbstractErrorDocument $document, array $errors = [])
    {
        return self::getErrorResponse($this->response, $document, $errors, $statusCode);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $statusCode
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public static function getGenericError(
        ResponseInterface $response,
        $statusCode,
        AbstractErrorDocument $document,
        array $errors = []
    ) {
        return self::getErrorResponse($response, $document, $errors, $statusCode);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
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
