<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument;

class FetchRelationshipResponse extends AbstractResponse
{
    /**
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function ok(
        $relationshipName,
        RequestInterface $request,
        ResponseInterface $response,
        AbstractCompoundDocument $document,
        $resource
    ) {
        return self::getDocumentRelationshipResponse(
            $relationshipName,
            $request,
            $response,
            $document,
            $resource,
            200
        );
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
}
