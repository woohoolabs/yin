<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Serializer;

use Psr\Http\Message\ServerRequestInterface;

interface DeserializerInterface
{
    /**
     * @return array|mixed|null
     */
    public function deserialize(ServerRequestInterface $request);
}
