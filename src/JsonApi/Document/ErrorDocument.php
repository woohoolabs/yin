<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Schema\Document\ErrorDocument;

class_alias(ErrorDocument::class, 'WoohooLabs\Yin\JsonApi\Document\ErrorDocument');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Document\ErrorDocument is deprecated, use ' . ErrorDocument::class . ' instead.',
    E_USER_DEPRECATED
);
