<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Data;

use Traversable;

interface DataInterface
{
    /**
     * @return array|null
     */
    public function getResource(string $type, string $id);

    public function hasPrimaryResources(): bool;

    public function hasPrimaryResource(string $type, string$id): bool;

    public function hasIncludedResources(): bool;

    public function hasIncludedResource(string $type, string $id): bool;

    /**
     * @param array|Traversable $transformedResources
     * @return $this
     */
    public function setPrimaryResources($transformedResources);

    /**
     * @return $this
     */
    public function addPrimaryResource(array $transformedResource);

    /**
     * @param array|Traversable $transformedResources
     * @return $this
     */
    public function setIncludedResources($transformedResources);

    /**
     * @return $this
     */
    public function addIncludedResource(array $transformedResource);

    /**
     * @return array|Traversable|null
     */
    public function transformPrimaryResources();

    /**
     * @return array|Traversable
     */
    public function transformIncludedResources();
}
