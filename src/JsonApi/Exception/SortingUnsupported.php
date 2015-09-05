<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class SortingUnsupported extends \Exception
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @param string $fieldName
     */
    public function __construct($fieldName)
    {
        parent::__construct("Sorting field '" . $fieldName . "'' is unsupported!");

        $this->fieldName = $fieldName;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }
}
