<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Schema\Transformable;

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
     * @param \WoohooLabs\Yin\JsonApi\Schema\Transformable $value
     */
    protected function addOptionalTransformedItemToArray(array &$array, $key, Transformable $value = null)
    {
        if ($value !== null) {
            $array[$key] = $value->transform();
        }
    }

    /**
     * @param array $array
     * @param string $key
     * @param array $values
     */
    protected function addOptionalTransformedCollectionToArray(array &$array, $key, array $values)
    {
        if (empty($values) === false) {
            foreach ($values as $value) {
                /** @var \WoohooLabs\Yin\JsonApi\Schema\Transformable $value */
                $array[$key][] = $value->transform();
            }
        }
    }
}
