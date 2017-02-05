<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Serializer;

use Psr\Http\Message\ResponseInterface;

class DefaultSerializer implements SerializerInterface
{
    public function serialize(ResponseInterface $response, int $responseCode, array $content): ResponseInterface
    {
        $response = $response->withStatus($responseCode);
        $response = $response->withHeader("Content-Type", "application/vnd.api+json");
        if ($response->getBody()->isSeekable()) {
            $response->getBody()->rewind();
        }
        $response->getBody()->write(json_encode($content));

        return $response;
    }

    /**
     * @return string
     */
    public function getBodyAsString(ResponseInterface $response): string
    {
        return $response->getBody()->__toString();
    }
}
