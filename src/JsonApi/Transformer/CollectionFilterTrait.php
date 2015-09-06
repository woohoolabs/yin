<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Exception\FilteringCriteriaUnsupported;
use WoohooLabs\Yin\JsonApi\Exception\SortingCriteriaUnsupported;
use WoohooLabs\Yin\JsonApi\Schema\Included;

trait CollectionFilterTrait
{
    /**
     * @param array $data
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param array $sortingFields
     * @return array
     */
    protected function sortByFields(&$data, Included $included, array $sortingFields)
    {
        $comparator = function ($a, $b) use ($sortingFields, $included) {
            foreach ($sortingFields as $sorting) {
                foreach ($sorting["orderBy"] as $field) {
                    $a = $this->getResourceField($a, $field, $included);
                    $b = $this->getResourceField($b, $field, $included);
                }

                $result = $this->performSortingComparison($sorting["field"], $a, $b);
                if ($result !== 0) {
                    return $result * $sorting["direction"];
                }
            }

            return 0;
        };

        usort($data, $comparator);
    }

    /**
     * @param array $data
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @param array $filteringFields
     * @return array
     */
    protected function filterByFields(&$data, Included $included, array $filteringFields)
    {
        $filter = function ($item) use ($filteringFields, $included) {
            foreach ($filteringFields as $filtering) {
                foreach ($filtering["field"] as $field) {
                    $item = $this->getResourceField($item, $field, $included);
                }

                $result = $this->performFilteringComparison(
                    $filtering["originalField"],
                    $item,
                    $filtering["operator"],
                    $filtering["value"]
                );
                if ($result === false) {
                    return false;
                }
            }

            return true;
        };

        // Filtering primary data and collecting relationship identifiers which can be included
        $remainingIncludableRelationships = [];
        foreach ($data as $key => $resource) {
            if ($filter($resource) === false) {
                unset($data[$key]);
            } else {
                $this->addRelationshipIdentifiers($remainingIncludableRelationships, $resource);
            }
        }

        // Filtering included data
        $included->filterResources(function ($type, $id) use ($remainingIncludableRelationships) {
            return isset($remainingIncludableRelationships[$type][$id]) ? true : false;
        });
    }

    /**
     * @param array $relationships
     * @param array $resource
     */
    protected function addRelationshipIdentifiers(&$relationships, $resource)
    {
        if (isset($resource["relationships"]) === false) {
            return;
        }

        foreach ($resource["relationships"] as $relationship) {
            if (isset($relationship["data"]) && $this->isAssociativeArray($relationship["data"]) === false) {
                foreach ($relationship["data"] as $item) {
                    if (isset($item["type"]) && isset($item["id"])) {
                        $relationships[$item["type"]][$item["id"]] = true;
                    }
                }
            }
        }
    }

    /**
     * @param mixed $resource
     * @param string $field
     * @param \WoohooLabs\Yin\JsonApi\Schema\Included $included
     * @return string|null
     */
    protected function getResourceField($resource, $field, Included $included)
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
     * @param string $operator
     * @param mixed $y
     * @return bool
     * @throws \WoohooLabs\Yin\JsonApi\Exception\FilteringCriteriaUnsupported
     */
    protected function performFilteringComparison($fieldName, $x, $operator, $y)
    {
        if (($x !== null && is_scalar($x) === false) || ($y !== null && is_scalar($y) === false)) {
            throw new FilteringCriteriaUnsupported($fieldName);
        }

        switch ($operator) {
            case "=":
                return $this->equals($x, $y);
            case "<":
                return $x < $y;
            case "<=":
                return $x <= $y;
            case ">":
                return $x > $y;
            case ">=":
                return $x >= $y;
        }

        return false;
    }

    /**
     * @param string $fieldName
     * @param mixed $x
     * @param mixed $y
     * @return int
     * @throws \WoohooLabs\Yin\JsonApi\Exception\SortingCriteriaUnsupported
     */
    protected function performSortingComparison($fieldName, $x, $y)
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
     * @return bool
     */
    protected function equals($x, $y)
    {
        if (is_bool($x)) {
            $y = boolval($y);
        } elseif(is_long($x)) {
            $y = intval($y);
        } elseif (is_double($x)) {
            $y = doubleval($y);
        } elseif (is_string($x)) {
            $y = print_r($y, true);
        }

        return $x === $y;
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

    /**
     * @param array $array
     * @return bool
     */
    private function isAssociativeArray(array $array)
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}
