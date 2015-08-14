<?php
namespace WoohooLabs\Yin\JsonApi\Response\Traits;

use Psr\Http\Message\ResponseInterface;

trait AcceptedTrait
{
    use GenericResponseTrait;

    /**
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function accepted()
    {
        return self::getAccepted($this->response);
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public static function getAccepted(ResponseInterface $response)
    {
        return $response->withStatus(202);
    }
}
