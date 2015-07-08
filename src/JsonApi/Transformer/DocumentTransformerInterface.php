<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

interface DocumentTransformerInterface
{
    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function transformResponse();
}
