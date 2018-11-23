<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Document;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;

interface ErrorDocumentInterface extends DocumentInterface
{
    /**
     * @return Error[]
     */
    public function getErrors(): array;

    public function getStatusCode(?int $statusCode): int;
}
