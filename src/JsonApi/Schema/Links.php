<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;

class Links implements Transformable
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\Link
     */
    private $self;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\Link
     */
    private $related;

    /**
     * @var array
     */
    private $links;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $self
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $related
     * @param array $links
     */
    public function __construct(Link $self = null, Link $related = null, array $links = null)
    {
        $this->self = $self;
        $this->related = $related;
        $this->links = $links;
    }

    /**
     * @return array
     */
    public function transform()
    {
        $links = [];

        foreach ($this->links as $link) {
            /** @var \WoohooLabs\Yin\JsonApi\Schema\Link $link */
            $links[] = $link->transform();
        }

        return $links;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $self
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    public function setSelf(Link $self)
    {
        $this->links["self"] = $self;

        return $this;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $related
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    public function setRelated(Link $related)
    {
        $this->links["related"] = $related;

        return $this;
    }

    /**
     * @param string $rel
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $link
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    public function addLink($rel, Link $link)
    {
        $this->links[$rel] = $link;

        return $this;
    }
}
