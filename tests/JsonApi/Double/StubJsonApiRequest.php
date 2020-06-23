<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\StreamFactory;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequest;
use WoohooLabs\Yin\JsonApi\Serializer\JsonDeserializer;

class StubJsonApiRequest extends JsonApiRequest
{
    public static function create(array $queryParams = []): StubJsonApiRequest
    {
        return new StubJsonApiRequest($queryParams);
    }

    public function __construct(array $queryParams = [])
    {
        $streamFactory = new StreamFactory();

        parent::__construct(
            new ServerRequest([], [], null, null, $streamFactory->createStream(), [], [], $queryParams),
            new DefaultExceptionFactory(),
            new JsonDeserializer()
        );
    }
}
