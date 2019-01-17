<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequest;
use WoohooLabs\Yin\JsonApi\Serializer\JsonDeserializer;
use Zend\Diactoros\ServerRequest;

class StubRequest extends JsonApiRequest
{
    public function __construct(array $queryParams = [])
    {
        parent::__construct(
            new ServerRequest([], [], null, null, "php://input", [], [], $queryParams),
            new DefaultExceptionFactory(),
            new JsonDeserializer()
        );
    }
}
