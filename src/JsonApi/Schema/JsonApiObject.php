<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema;

class JsonApiObject
{
    use MetaTrait;

    /**
     * @var string
     */
    private $version;

    public function __construct(string $version, array $meta = [])
    {
        $this->version = $version;
        $this->meta = $meta;
    }

    public function transform(): array
    {
        $result = [];

        if ($this->version) {
            $result["version"] = $this->version;
        }

        if (empty($this->meta) === false) {
            $result["meta"] = $this->meta;
        }

        return $result;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}
