<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;

class JsonApi implements TransformableInterface
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
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @return array
     */
    public function transform(Criteria $criteria)
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
