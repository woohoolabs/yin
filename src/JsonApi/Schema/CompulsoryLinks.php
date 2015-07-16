<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class CompulsoryLinks extends Links
{
    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $self
     * @param \WoohooLabs\Yin\JsonApi\Schema\Link $related
     * @param array $links
     */
    public function __construct(Link $self = null, Link $related = null, array $links = [])
    {
        parent::__construct([]);

        if ($self !== null) {
            $this->links["self"] = $self;
        }

        if ($related !== null) {
            $this->links["related"] = $related;
        }

        $this->links = array_merge($this->links, $links);
    }
}
