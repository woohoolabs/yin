<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

interface JsonApiExceptionInterface
{
    /**
     * @return \WoohooLabs\Yin\JsonApi\Document\AbstractErrorDocument
     */
    public function getErrorDocument();
}
