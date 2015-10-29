<?php
namespace Src\Utils\Hateoas;

use WoohooLabs\Yin\JsonApi\Schema\Links;

class RelativeLinks extends Links
{
    /**
     * @param array $links
     */
    public function __construct(array $links)
    {
        parent::__construct(gethostname(), $links);
    }
}
