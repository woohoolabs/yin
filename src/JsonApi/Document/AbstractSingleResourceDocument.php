<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractSingleResourceDocument;

class_alias(AbstractSingleResourceDocument::class, 'WoohooLabs\Yin\JsonApi\Document\AbstractSingleResourceDocument');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Document\AbstractSingleResourceDocument is deprecated, use ' .
    AbstractSingleResourceDocument::class . ' instead.',
    E_USER_DEPRECATED
);
