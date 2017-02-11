<?php
namespace WoohooLabs\Yin\JsonApi\Serializer;

use Psr\Http\Message\ResponseInterface;

class DefaultSerializer implements SerializerInterface
{
    /**
     * @var int
     */
    private $options;

    /**
     * @var int
     */
    private $depth;

    /**
     * @param int $options
     * @param int $depth
     */
    public function __construct($options = 0, $depth = 512)
    {
        $this->options = $options;
        $this->depth = $depth;
    }

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
        $response->getBody()->write(json_encode($content, $this->options, $this->depth));

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
