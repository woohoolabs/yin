<?php
namespace WoohooLabs\Yin\Examples\Book\JsonApi\Hydrator;

use WoohooLabs\Yin\Examples\Utils\Uuid;
use WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdNotSupported;
use WoohooLabs\Yin\JsonApi\Hydrator\CreateHydrator;

class BookHydator extends CreateHydrator
{
    /**
     * @return string|array
     */
    protected function getAcceptedType()
    {
        return "book";
    }

    /**
     * @param string $clientGeneratedId
     * @return true
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdNotSupported
     * @throws \WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdAlreadyExists
     */
    protected function validateClientGeneratedId($clientGeneratedId)
    {
        throw new ClientGeneratedIdNotSupported($clientGeneratedId);
    }

    /**
     * @return string
     */
    protected function generateId()
    {
        return Uuid::generate();
    }

    /**
     * @param array $resource
     * @param string $id
     * @return mixed
     */
    protected function setId($resource, $id)
    {
        $resource["id"] = $id;

        return $resource;
    }

    /**
     * @return array
     */
    protected function getAttributeHydrator()
    {
        return [
            "title" => function(array $resource, $attribute, $data)  { $resource["title"] = $attribute; return $resource; },
            "pages" => function(array &$resource, $attribute, $data) { $resource["pages"] = $attribute; }
        ];
    }

    /**
     * @return array
     */
    protected function getRelationshipHydrator()
    {
        return [

        ];
    }
}
