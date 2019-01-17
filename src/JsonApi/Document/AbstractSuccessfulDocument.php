<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractSuccessfulDocument;

class_alias(AbstractSuccessfulDocument::class, 'WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument is deprecated, use ' .
    AbstractSuccessfulDocument::class . ' instead.',
    E_USER_DEPRECATED
);
