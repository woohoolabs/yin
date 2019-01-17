<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

class_alias(AbstractJsonApiException::class, 'WoohooLabs\Yin\JsonApi\Exception\JsonApiException');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Exception\JsonApiException is deprecated, use ' . AbstractJsonApiException::class . ' instead.',
    E_USER_DEPRECATED
);
