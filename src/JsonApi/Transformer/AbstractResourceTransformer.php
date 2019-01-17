<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

class_alias(AbstractResource::class, 'WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer is deprecated, use ' . AbstractResource::class . ' instead.',
    E_USER_DEPRECATED
);
