<?php
namespace WoohooLabs\Yin\Examples\User\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Attributes;
use WoohooLabs\Yin\JsonApi\Schema\Link;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class ContactResourceTransformer extends AbstractResourceTransformer
{
    /**
     * @param array $contact
     * @return string
     */
    public function getType($contact)
    {
        return "contact";
    }

    /**
     * @param array $contact
     * @return string
     */
    public function getId($contact)
    {
        return $contact["id"];
    }

    /**
     * @param array $contact
     * @return array
     */
    public function getMeta($contact)
    {
        return [];
    }

    /**
     * @param array $contact
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links|null
     */
    public function getLinks($contact)
    {
        return new Links(
            [
                "self" => new Link("http://example.com/api/contacts/" . $this->getId($contact))
            ]
        );
    }

    /**
     * @param array $contact
     * @return \WoohooLabs\Yin\JsonApi\Schema\Attributes|null
     */
    public function getAttributes($contact)
    {
        return new Attributes(
            [
                $contact["type"] => function(array $contact) { return $contact["value"]; },
            ]
        );
    }

    /**
     * @param array $contact
     * @return \WoohooLabs\Yin\JsonApi\Schema\Relationships|null
     */
    public function getRelationships($contact)
    {
        return null;
    }
}
