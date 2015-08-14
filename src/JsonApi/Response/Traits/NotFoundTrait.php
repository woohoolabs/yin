<?php
namespace WoohooLabs\Yin\JsonApi\Response\Traits;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument;

trait NotFoundTrait
{
    use GenericResponseTrait;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function notFound(AbstractErrorDocument $document, array $errors = [])
    {
        return self::getNotFound($this->response, $document, $errors);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public static function getNotFound(ResponseInterface $response, AbstractErrorDocument $document, array $errors = [])
    {
        return self::getErrorResponse($response, $document, $errors, 404);
    }
}
