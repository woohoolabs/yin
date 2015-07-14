<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class JsonApi implements SimpleTransformableInterface
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
        $result = [
            "version" => $this->version
        ];

        if ($this->meta !== null) {
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

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }
}
