<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Exception\SortingCriteriaUnsupported;
use WoohooLabs\Yin\JsonApi\Schema\Included;

trait CollectionFilterTrait
{
    /**
     * @param array $data
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param array $sortingFields
     */
    protected function sortByFields(&$data, Included $included, array $sortingFields)
    {
        $comparator = function ($a, $b) use ($sortingFields, $included) {
            foreach ($sortingFields as $sorting) {
                foreach ($sorting["orderBy"] as $field) {
                    $a = $this->getResourceField($a, $field, $included);
                    $b = $this->getResourceField($b, $field, $included);
                }

                $result = $this->compare($sorting["field"], $a, $b);
                if ($result !== 0) {
                    return $result * $sorting["direction"];
                }
            }

            return 0;
        };

        usort($data, $comparator);
    }

    /**
     * @param mixed $resource
     * @param string $field
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @return string|null
     */
    private function getResourceField($resource, $field, Included $included)
    {
        if ($resource === null) {
            return null;
        }

        if ($field === "type" || $field === "id") {
            return isset($resource[$field]) ? $resource[$field] : null;
        }

        if (isset($resource["attributes"][$field])) {
            return $resource["attributes"][$field];
        }

        if (isset($resource["relationships"][$field]["data"]["type"]) &&
            isset($resource["relationships"][$field]["data"]["id"])
        ) {
            $type = $resource["relationships"][$field]["data"]["type"];
            $id = $resource["relationships"][$field]["data"]["id"];
            return $included->getResource($type, $id);
        }

        return null;
    }

    /**
     * @param string $fieldName
     * @param mixed $x
     * @param mixed $y
     * @return int
     * @throws \WoohooLabs\Yin\JsonApi\Exception\SortingCriteriaUnsupported
     */
    private function compare($fieldName, $x, $y)
    {
        if (($x !== null && is_scalar($x) === false) || ($y !== null && is_scalar($y) === false)) {
            throw new SortingCriteriaUnsupported($fieldName);
        }

        if ($x === null || $y === null) {
            return $this->compareNull($x, $y);
        }

        if (is_bool($x) && is_bool($y)) {
            return $this->compareNumeric($x, $y);
        }

        if (is_numeric($x) && is_numeric($y)) {
            return $this->compareNumeric($x, $y);
        }

        return $this->compareString($x, $y);
    }

    /**
     * @param mixed $x
     * @param mixed $y
     * @return int
     */
    private function compareNull($x, $y)
    {
        return $x !== null && $y === null ? 1 : ($y === null && $y === null ? 0 : -1);
    }

    /**
     * @param int|float|double|bool $x
     * @param int|float|double|bool $y
     * @return int
     */
    private function compareNumeric($x, $y)
    {
        return $x > $y ? 1 : ($x === $y ? 0 : -1);
    }

    /**
     * @param bool $x
     * @param bool $y
     * @return int
     */
    private function compareString($x, $y)
    {
        return strnatcasecmp($x, $y);
    }
}
