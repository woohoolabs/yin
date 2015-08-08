<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument;

class UpdateResponse extends AbstractResponse
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function ok(Request $request, ResponseInterface $response, AbstractCompoundDocument $document, $resource)
    {
        return self::getDocumentResourceResponse($request, $response, $document, $resource, 200);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Request $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function okWithMeta(
        Request $request,
        ResponseInterface $response,
        AbstractCompoundDocument $document,
        $resource
    ) {
        return self::getDocumentMetaResponse($request, $response, $document, $resource, 200);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public static function accepted(ResponseInterface $response)
    {
        return $response->withStatus(202);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public static function noContent(ResponseInterface $response)
    {
        return $response->withStatus(204);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public static function forbidden(ResponseInterface $response, AbstractErrorDocument $document, array $errors = [])
    {
        return self::getErrorResponse($response, $document, $errors, 403);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public static function notFound(ResponseInterface $response, AbstractErrorDocument $document, array $errors = [])
    {
        return self::getErrorResponse($response, $document, $errors, 404);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public static function conflict(ResponseInterface $response, AbstractErrorDocument $document, array $errors = [])
    {
        return self::getErrorResponse($response, $document, $errors, 409);
    }
}
