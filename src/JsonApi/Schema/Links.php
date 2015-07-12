<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Transformer\TransformerTrait;

class Links implements SimpleTransformableInterface
{
    use TransformerTrait;

    /**
     * @var array
     */
    protected $links;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $self
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $related
     */
    public function __construct(Link $self = null, Link $related = null)
    {
        $this->links = [];
        $this->addOptionalItemToArray($this->links, "self", $self);
        $this->addOptionalItemToArray($this->links, "related", $related);
    }

    /**
     * @return array
     */
    public function transform()
    {
        $links = [];

        foreach ($this->links as $rel => $link) {
            /** @var \WoohooLabs\Yin\JsonApi\Schema\Link $link */
            $links[$rel] = $link->transform();
        }

        return $links;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getSelf()
    {
        return isset($this->links["self"]) ? $this->links["self"] : null;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $self
     * @return $this
     */
    public function setSelf(Link $self)
    {
        $this->links["self"] = $self;

        return $this;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getRelated()
    {
        return isset($this->links["related"]) ? $this->links["related"] : null;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $related
     * @return $this
     */
    public function setRelated(Link $related)
    {
        $this->links["related"] = $related;

        return $this;
    }

    /**
     * @param string $rel
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $link
     * @return $this
     */
    public function addLink($rel, Link $link)
    {
        $this->links[$rel] = $link;

        return $this;
    }
}
