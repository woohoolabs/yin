<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\Book\JsonApi\Resource;

use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractResourceTransformer;

class PublisherResourceTransformer extends AbstractResourceTransformer
{
    /**
     * @var RepresentativeResourceTransformer
     */
    private $representativeTransformer;

    public function __construct(RepresentativeResourceTransformer $representativeTransformer)
    {
        $this->representativeTransformer = $representativeTransformer;
    }

    /**
     * Provides information about the "type" member of the current resource.
     *
     * The method returns the type of the current resource.
     *
     * @param array $publisher
     */
    public function getType($publisher): string
    {
        return "publishers";
    }

    /**
     * Provides information about the "id" member of the current resource.
     *
     * The method returns the ID of the current resource which should be a UUID.
     *
     * @param array $publisher
     */
    public function getId($publisher): string
    {
        return (string) $publisher["id"];
    }

    /**
     * Provides information about the "meta" member of the current resource.
     *
     * The method returns an array of non-standard meta information about the resource. If
     * this array is empty, the member won't appear in the response.
     *
     * @param array $publisher
     */
    public function getMeta($publisher): array
    {
        return [];
    }

    /**
     * Provides information about the "links" member of the current resource.
     *
     * The method returns a new Links schema object if you want to provide linkage
     * data about the resource or null if it should be omitted from the response.
     *
     * @param array $publisher
     */
    public function getLinks($publisher): ?ResourceLinks
    {
        return null;
    }

    /**
     * Provides information about the "attributes" member of the current resource.
     *
     * The method returns an array where the keys signify the attribute names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return the value of the corresponding attribute.
     *
     * @param array $publisher
     * @return callable[]
     */
    public function getAttributes($publisher): array
    {
        return [
            "name" => function (array $publisher) {
                return $publisher["name"];
            },
        ];
    }

    /**
     * Returns an array of relationship names which are included in the response by default.
     *
     * @param array $publisher
     */
    public function getDefaultIncludedRelationships($publisher): array
    {
        return [];
    }

    /**
     * Provides information about the "relationships" member of the current resource.
     *
     * The method returns an array where the keys signify the relationship names,
     * while the values are callables receiving the domain object as an argument,
     * and they should return a new relationship instance (to-one or to-many).
     *
     * @param array $publisher
     * @return callable[]
     */
    public function getRelationships($publisher): array
    {
        return [
            "representative" => function ($publisher) {
                return
                    ToOneRelationship::create()
                        ->setData($publisher["representative"], $this->representativeTransformer)
                    ;
            }
        ];
    }
}
