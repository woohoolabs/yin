<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class JsonApi implements Transformable
{
    /**
     * @var string
     */
    private $version;

    /**
     * @var array
     */
    private $meta;

    /**
     * @param string $version
     * @param array $meta
     */
    public function __construct($version, array $meta = null)
    {
        $this->version = $version;
        $this->meta = $meta;
    }

    /**
     * @return array
     */
    public function transform()
    {
        return [
            "version" => $this->version,
            "meta" => $this->meta
        ];
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }
}
