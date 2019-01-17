<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Schema\Link\Link;

class_alias(Link::class, 'WoohooLabs\Yin\JsonApi\Schema\Link');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Schema\Link is deprecated, use ' . Link::class . ' instead.',
    E_USER_DEPRECATED
);
