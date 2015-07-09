<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Schema\Included;
use WoohooLabs\Yin\JsonApi\Schema\IncludedTransformableInterface;
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
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param array $array
     * @param string $key
     * @param \WoohooLabs\Yin\JsonApi\Schema\TransformableInterface $value
     */
    protected function addOptionalTransformedItemToArray(
        Criteria $criteria,
        array &$array,
        $key,
        TransformableInterface $value = null
    ) {
        if ($value !== null) {
            $array[$key] = $value->transform($criteria);
        }
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param array $array
     * @param string $key
     * @param \WoohooLabs\Yin\JsonApi\Schema\IncludedTransformableInterface $value
     */
    protected function addOptionalIncludedTransformedItemToArray(
        Included $included,
        Criteria $criteria,
        array &$array,
        $key,
        IncludedTransformableInterface $value = null
    ) {
        if ($value !== null) {
            $array[$key] = $value->transform($included, $criteria);
        }
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param array $array
     * @param string $key
     * @param array $values
     */
    protected function addOptionalTransformedCollectionToArray(Criteria $criteria, array &$array, $key, array $values)
    {
        if (empty($values) === false) {
            foreach ($values as $value) {
                /** @var \WoohooLabs\Yin\JsonApi\Schema\TransformableInterface $value */
                $array[$key][] = $value->transform($criteria);
            }
        }
    }
}
