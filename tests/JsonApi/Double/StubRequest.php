<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Serializer\JsonDeserializer;
use Zend\Diactoros\ServerRequest;

class StubRequest extends Request
{
    public static function create(array $queryParams = []): StubRequest
    {
        return new StubRequest($queryParams);
    }

    public function __construct(array $queryParams = [])
    {
        parent::__construct(
            new ServerRequest([], [], null, null, "php://input", [], [], $queryParams),
            new DefaultExceptionFactory(),
            new JsonDeserializer()
        );
    }
}
