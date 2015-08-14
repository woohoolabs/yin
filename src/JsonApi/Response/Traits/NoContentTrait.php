<?php
namespace WoohooLabs\Yin\JsonApi\Response\Traits;

use Psr\Http\Message\ResponseInterface;

trait NoContentTrait
{
    use GenericResponseTrait;

    /**
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function noContent()
    {
        return self::getNoContent($this->response);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public static function getNoContent(ResponseInterface $response)
    {
        return $response->withStatus(204);
    }
}
