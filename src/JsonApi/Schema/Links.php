<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class Links
{
    /**
     * @var array
     */
    protected $links;

    /**
     * @param array $links
     * @return $this
     */
    public static function create(array $links = [])
    {
        return new self($links);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $self
     * @return $this
     */
    public static function createWithSelf(Link $self)
    {
        return new self(["self" => $self]);
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $related
     * @return $this
     */
    public static function createWithRelated(Link $related)
    {
        return new self(["related" => $related]);
    }

    /**
     * @param array $links
     */
    public function __construct(array $links = [])
    {
        $this->links = $links;
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
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getFirst()
    {
        return isset($this->links["first"]) ? $this->links["first"] : null;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $first
     * @return $this
     */
    public function setFirst(Link $first)
    {
        $this->links["first"] = $first;

        return $this;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getLast()
    {
        return isset($this->links["last"]) ? $this->links["last"] : null;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $last
     * @return $this
     */
    public function setLast(Link $last)
    {
        $this->links["last"] = $last;

        return $this;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getPrev()
    {
        return isset($this->links["prev"]) ? $this->links["prev"] : null;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $prev
     * @return $this
     */
    public function setPrev(Link $prev)
    {
        $this->links["prev"] = $prev;

        return $this;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Link|null
     */
    public function getNext()
    {
        return isset($this->links["next"]) ? $this->links["next"] : null;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $next
     * @return $this
     */
    public function setNext(Link $next)
    {
        $this->links["next"] = $next;

        return $this;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link[] $links
     * @return $this
     */
    public function setPaginationLinks(array $links)
    {
        if (isset($links["first"])) {
            $this->setFirst($links["first"]);
        }
        if (isset($links["last"])) {
            $this->setLast($links["last"]);
        }
        if (isset($links["next"])) {
            $this->setNext($links["next"]);
        }
        if (isset($links["prev"])) {
            $this->setPrev($links["prev"]);
        }

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
