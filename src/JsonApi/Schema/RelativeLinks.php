<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class RelativeLinks extends Links
{
    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @param string $baseUri
     * @param array $links
     * @return $this
     */
    public static function create($baseUri, array $links = [])
    {
        return new self($baseUri, $links);
    }

    /**
     * @param string $baseUri
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $self
     * @return $this
     */
    public static function createWithSelf($baseUri, Link $self)
    {
        return new self($baseUri, ["self" => $self]);
    }

    /**
     * @param string $baseUri
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $related
     * @return $this
     */
    public static function createWithRelated($baseUri, Link $related)
    {
        return new self($baseUri, ["related" => $related]);
    }

    /**
     * @param string $baseUri
     * @param array $links
     */
    public function __construct($baseUri, array $links = [])
    {
        $this->baseUri = $baseUri;
        parent::__construct($links);
    }

    /**
     * @return array
     */
    public function transform()
    {
        $links = [];

        foreach ($this->links as $rel => $link) {
            /** @var \WoohooLabs\Yin\JsonApi\Schema\Link $link */
            $links[$rel] = $link->transformRelative($this->baseUri);
        }

        return $links;
    }
}
