<?php
namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Request\Request;
use Zend\Diactoros\ServerRequest as DiactorosRequest;

class StubRequest extends Request
{
    public function __construct()
    {
        parent::__construct(new DiactorosRequest(), new DefaultExceptionFactory());
    }
}
