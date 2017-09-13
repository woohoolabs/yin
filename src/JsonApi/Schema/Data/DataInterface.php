<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Data;

interface DataInterface
{
    public function getResource(string $type, string $id): ?array;

    public function hasPrimaryResources(): bool;

    public function hasPrimaryResource(string $type, string$id): bool;

    public function hasIncludedResources(): bool;

    public function hasIncludedResource(string $type, string $id): bool;

    /**
     * @return $this
     */
    public function setPrimaryResources(iterable $transformedResources);

    /**
     * @return $this
     */
    public function addPrimaryResource(array $transformedResource);

    /**
     * @return $this
     */
    public function setIncludedResources(iterable $transformedResources);

    /**
     * @return $this
     */
    public function addIncludedResource(array $transformedResource);

    public function transformPrimaryResources(): ?iterable;

    public function transformIncludedResources(): iterable;
}
