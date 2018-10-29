<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use Throwable;
use WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument;

interface JsonApiExceptionInterface extends Throwable
{
    public function getErrorDocument(): AbstractErrorDocument;
}
