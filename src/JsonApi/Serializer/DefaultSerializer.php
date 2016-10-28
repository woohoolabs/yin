<?php
namespace WoohooLabs\Yin\JsonApi\Serializer;

use Psr\Http\Message\ResponseInterface;

class DefaultSerializer implements SerializerInterface
{
    /**
     * @param ResponseInterface $response
     * @param int $responseCode
     * @param array $content
     * @return ResponseInterface
     */
    public function serialize(ResponseInterface $response, $responseCode, array $content)
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
    public function getBodyAsString(ResponseInterface $response)
    {
        return $response->getBody()->__toString();
    }
}
