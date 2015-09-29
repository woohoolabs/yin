<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

interface JsonApiExceptionInterface
{
    /**
     * @return \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument
     */
    public function getErrorDocument();
}
