<?php

namespace WoohooLabs\Yin\JsonApi\Serializer;

use Psr\Http\Message\ResponseInterface;

interface SerializerInterface
{
    /**
     * @param ResponseInterface $response
     * @param int $responseCode
     * @param array $content
     * @return ResponseInterface
     */
    public function serialize(ResponseInterface $response, $responseCode, array $content);

    /**
     * @return string
     */
    public function getBodyAsString(ResponseInterface $response);
}
