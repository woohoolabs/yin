<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Serializer;

use Psr\Http\Message\ResponseInterface;

interface SerializerInterface
{
    public function serialize(ResponseInterface $response, array $content): ResponseInterface;

    public function getBodyAsString(ResponseInterface $response): string;
}
