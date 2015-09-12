<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

abstract class AbstractResponse
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
     * Returns a successful response with the given status code.
     *
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function genericSuccess($statusCode)
    {
        return $this->response->withStatus($statusCode);
    }

    /**
     * Returns an error response with the given status code, containing a document in the body with the errors.
     *
     * @param int $statusCode
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param \WoohooLabs\Yin\JsonApi\Schema\Error[] $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function genericError($statusCode, AbstractErrorDocument $document, array $errors = [])
    {
        return $this->getErrorResponse($this->response, $document, $errors, $statusCode);
    }

    /**
     * Returns the original PSR-7 response object.
     *
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
     * @param mixed $domainObject
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getDocumentResourceResponse(
        RequestInterface $request,
        ResponseInterface $response,
        AbstractCompoundDocument $document,
        $domainObject,
        $statusCode
    ) {
        return $document->getResponse($response, $domainObject, $request, $statusCode);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $domainObject
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getDocumentMetaResponse(
        ResponseInterface $response,
        AbstractCompoundDocument $document,
        $domainObject,
        $statusCode
    ) {
        return $document->getMetaResponse($response, $domainObject, $statusCode);
    }

    /**
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $domainObject
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getDocumentRelationshipResponse(
        $relationshipName,
        RequestInterface $request,
        ResponseInterface $response,
        AbstractCompoundDocument $document,
        $domainObject,
        $statusCode
    ) {
        return $document->getRelationshipResponse($relationshipName, $response, $domainObject, $request, $statusCode);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
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
