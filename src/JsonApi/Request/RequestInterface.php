<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request;

class_alias(JsonApiRequestInterface::class, 'WoohooLabs\Yin\JsonApi\Request\RequestInterface');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Request\RequestInterface is deprecated, use ' . JsonApiRequestInterface::class . ' instead.',
    E_USER_DEPRECATED
);
