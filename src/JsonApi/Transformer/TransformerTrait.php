<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Schema\SimpleTransformableInterface;
use WoohooLabs\Yin\JsonApi\Schema\TransformableInterface;

trait TransformerTrait
{
    /**
     * @param array $array
     * @param string $key
     * @param mixed $value
     */
    protected function addOptionalItemToArray(array &$array, $key, $value)
    {
        if (empty($value) === false) {
            $array[$key] = $value;
        }
    }

    /**
     * @param array $array
     * @param string $key
     * @param \WoohooLabs\Yin\JsonApi\Schema\SimpleTransformableInterface $value
     */
    protected function addOptionalSimpleTransformedItemToArray(
        array &$array,
        $key,
        SimpleTransformableInterface $value = null
    ) {
        if ($value !== null) {
            $array[$key] = $value->transform();
        }
    }

    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param array $array
     * @param string $key
     * @param \WoohooLabs\Yin\JsonApi\Schema\TransformableInterface $value
     */
    protected function addOptionalTransformedItemToArray(
        $resource,
        Criteria $criteria,
        array &$array,
        $key,
        TransformableInterface $value = null
    ) {
        if ($value !== null) {
            $array[$key] = $value->transform($resource, $criteria);
        }
    }

    /**
     * @param array $array
     * @param string $key
     * @param array $values
     */
    protected function addOptionalSimpleTransformedCollectionToArray(array &$array, $key, array $values)
    {
        if (empty($values) === false) {
            foreach ($values as $value) {
                /** @var \WoohooLabs\Yin\JsonApi\Schema\SimpleTransformableInterface $value */
                $array[$key][] = $value->transform();
            }
        }
    }
}
