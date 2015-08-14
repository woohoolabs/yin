<?php
namespace WoohooLabs\Yin\JsonApi\Response\Traits;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument;

trait ForbiddenTrait
{
    use GenericResponseTrait;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function forbidden(AbstractErrorDocument $document, array $errors = [])
    {
        return self::getForbidden($this->response, $document, $errors);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public static function getForbidden(
        ResponseInterface $response,
        AbstractErrorDocument $document,
        array $errors = []
    ) {
        return self::getErrorResponse($response, $document, $errors, 403);
    }
}
