<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Serializer;

use Psr\Http\Message\ResponseInterface;

interface SerializerInterface
{
    public function serialize(ResponseInterface $response, int $responseCode, array $content): ResponseInterface;

    public function getBodyAsString(ResponseInterface $response): string;
}
