<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;

class_alias(Error::class, 'WoohooLabs\Yin\JsonApi\Schema\Error');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Schema\Error is deprecated, use ' . Error::class . ' instead.',
    E_USER_DEPRECATED
);
