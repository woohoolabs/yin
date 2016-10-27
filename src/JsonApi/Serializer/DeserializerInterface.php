<?php

namespace WoohooLabs\Yin\JsonApi\Serializer;

use Psr\Http\Message\ResponseInterface;

interface DeserializerInterface
{
    /**
     * @return string
     */
    public function getBodyAsString(ResponseInterface $response);

    /**
     * @return array|null
     */
    public function deserializeBody(ResponseInterface $response);
}
