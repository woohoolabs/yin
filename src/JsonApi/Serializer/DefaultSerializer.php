<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Serializer;

use Psr\Http\Message\ResponseInterface;

class DefaultSerializer implements SerializerInterface
{
    public function serialize(ResponseInterface $response, int $responseCode, array $content): ResponseInterface
    {
        $result = $response
            ->withStatus($responseCode)
            ->withHeader("Content-Type", "application/vnd.api+json");

        if ($result->getBody()->isSeekable()) {
            $result->getBody()->rewind();
        }
        $result->getBody()->write(json_encode($content));

        return $response;
    }

    public function getBodyAsString(ResponseInterface $response): string
    {
        return $response->getBody()->__toString();
    }
}
