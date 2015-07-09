<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class PaginatedLinks extends Links
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $self
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $related
     */
    public function __construct(Link $self = null, Link $related = null)
    {
        parent::__construct($self, $related);
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
    public function setFirst($first)
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
    public function setLast($last)
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
    public function setPrev($prev)
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
    public function setNext($next)
    {
        $this->links["next"] = $next;

        return $this;
    }
}
