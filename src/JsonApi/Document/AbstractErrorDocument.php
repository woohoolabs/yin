<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractErrorDocument;

class_alias(AbstractErrorDocument::class, 'WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument is deprecated, use ' . AbstractErrorDocument::class . ' instead.',
    E_USER_DEPRECATED
);
