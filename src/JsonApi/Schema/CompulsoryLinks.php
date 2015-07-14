<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Transformer\TransformerTrait;

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
        $this->addOptionalItemToArray($this->links, "self", $self);
        $this->addOptionalItemToArray($this->links, "related", $related);

        $this->links = array_merge($this->links, $links);
    }
}
