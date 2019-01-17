<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractErrorDocument;

interface JsonApiExceptionInterface
{
    public function getErrorDocument(): AbstractErrorDocument;
}
