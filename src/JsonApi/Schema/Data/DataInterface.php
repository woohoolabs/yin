<?php
namespace WoohooLabs\Yin\JsonApi\Schema\Data;

interface DataInterface
{
    /**
     * @param string $type
     * @param string $id
     * @return array|null
     */
    public function getResource($type, $id);

    /**
     * @return bool
     */
    public function hasPrimaryResources();

    /**
     * @param string $type
     * @param string $id
     * @return bool
     */
    public function hasPrimaryResource($type, $id);

    /**
     * @return bool
     */
    public function hasIncludedResources();

    /**
     * @param string $type
     * @param string $id
     * @return bool
     */
    public function hasIncludedResource($type, $id);

    /**
     * @param \Traversable|array $transformedResources
     * @return $this
     */
    public function setPrimaryResources($transformedResources);

    /**
     * @param array $transformedResource
     * @return $this
     */
    public function addPrimaryResource(array $transformedResource);

    /**
     * @param \Traversable|array $transformedResources
     * @return $this
     */
    public function setIncludedResources($transformedResources);

    /**
     * @param array $transformedResource
     * @return $this
     */
    public function addIncludedResource(array $transformedResource);

    /**
     * @return \Traversable|array|null
     */
    public function transformPrimaryResources();

    /**
     * @return \Traversable|array
     */
    public function transformIncludedResources();
}
