<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema;

class JsonApi
{
    use MetaTrait;

    /**
     * @var string
     */
    private $version;

    /**
     * @param string $version
     * @param array $meta
     */
    public function __construct($version, array $meta = [])
    {
        $this->version = $version;
        $this->meta = $meta;
    }

    /**
     * @return array
     */
    public function transform()
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

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}
