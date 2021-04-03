<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema;

trait MetaTrait
{
    protected array $meta = [];

    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @return $this
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }
}
