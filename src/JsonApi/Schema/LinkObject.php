<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Schema\Link\LinkObject;

class_alias(LinkObject::class, 'WoohooLabs\Yin\JsonApi\Schema\LinkObject');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Schema\LinkObject is deprecated, use ' . LinkObject::class . ' instead.',
    E_USER_DEPRECATED
);
