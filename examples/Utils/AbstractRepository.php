<?php
namespace WoohooLabs\Yin\Examples\Utils;

abstract class AbstractRepository
{
    /**
     * @param string $id
     * @param array $items
     * @return array|null
     */
    public static function getItemById($id, array $items)
    {
        foreach ($items as $item) {
            if (isset($item["id"]) && $item["id"] === $id) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @param array $ids
     * @param array $items
     * @return array
     */
    public static function getItemsByIds(array $ids, array $items)
    {
        $result = [];

        foreach ($ids as $id) {
            foreach ($items as $item) {
                if (isset($item["id"]) && $item["id"] === $id) {
                    $result[] = $item;
                }
            }
        }

        return $result;
    }
}
