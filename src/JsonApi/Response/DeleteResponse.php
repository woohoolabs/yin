<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument;

class DeleteResponse extends AbstractResponse
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function okWithMeta(
        RequestInterface $request,
        ResponseInterface $response,
        AbstractCompoundDocument $document,
        $resource
    ) {
        $response = self::getDocumentMetaResponse($request, $response, $document, $resource, 200);

        return $response;
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
}
