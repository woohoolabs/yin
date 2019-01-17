<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Schema\Error\ErrorSource;

class_alias(ErrorSource::class, 'WoohooLabs\Yin\JsonApi\Schema\ErrorSource');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Schema\ErrorSource is deprecated, use ' . ErrorSource::class . ' instead.',
    E_USER_DEPRECATED
);
