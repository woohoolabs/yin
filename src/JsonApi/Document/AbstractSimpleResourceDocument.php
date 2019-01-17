<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractSimpleResourceDocument;

class_alias(AbstractSimpleResourceDocument::class, 'WoohooLabs\Yin\JsonApi\Document\AbstractSimpleResourceDocument');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument is deprecated, use ' .
    AbstractSimpleResourceDocument::class . ' instead.',
    E_USER_DEPRECATED
);
