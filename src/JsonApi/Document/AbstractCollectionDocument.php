<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractCollectionDocument;

class_alias(AbstractCollectionDocument::class, 'WoohooLabs\Yin\JsonApi\Document\AbstractCollectionDocument');

trigger_error(
    'Class WoohooLabs\Yin\JsonApi\Document\AbstractCollectionDocument is deprecated, use ' .
    AbstractCollectionDocument::class . ' instead.',
    E_USER_DEPRECATED
);
