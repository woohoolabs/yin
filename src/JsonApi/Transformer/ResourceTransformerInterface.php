<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface;

class_alias(ResourceInterface::class, 'WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformerInterface is deprecated, use ' . ResourceInterface::class . ' instead.',
    E_USER_DEPRECATED
);
