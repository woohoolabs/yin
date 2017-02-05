<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument;

interface JsonApiExceptionInterface
{
    public function getErrorDocument(): AbstractErrorDocument;
}
