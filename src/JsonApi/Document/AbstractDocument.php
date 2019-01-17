<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractDocument;

class_alias(AbstractDocument::class, 'WoohooLabs\Yin\JsonApi\Document\AbstractDocument');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Document\AbstractDocument is deprecated, use ' . AbstractDocument::class . ' instead.',
    E_USER_DEPRECATED
);
