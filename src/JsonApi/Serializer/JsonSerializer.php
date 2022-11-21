<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Serializer;

use Psr\Http\Message\ResponseInterface;

use function json_encode;

class JsonSerializer implements SerializerInterface
{
    /**
     * @var int
     */
    private $options;

    /**
     * @var positive-int
     */
    private $depth;

    /**
     * @param positive-int $depth
     */
    public function __construct(int $options = 0, int $depth = 512)
    {
        $this->options = $options;
        $this->depth = $depth;
    }

    public function serialize(ResponseInterface $response, array $content): ResponseInterface
    {
        if ($response->getBody()->isSeekable()) {
            $response->getBody()->rewind();
        }

        $body = json_encode($content, $this->options, $this->depth);
        if ($body !== false) {
            $response->getBody()->write($body);
        }

        return $response;
    }

    public function getBodyAsString(ResponseInterface $response): string
    {
        return $response->getBody()->__toString();
    }
}
