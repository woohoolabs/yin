<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request;

class_alias(JsonApiRequest::class, 'WoohooLabs\Yin\JsonApi\Request\Request');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Request\Request is deprecated, use ' . JsonApiRequest::class . ' instead.',
    E_USER_DEPRECATED
);
