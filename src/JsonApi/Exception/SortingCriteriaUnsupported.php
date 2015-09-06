<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class SortingCriteriaUnsupported extends \Exception
{
    /**
     * @var string
     */
    private $criteria;

    /**
     * @param string $criteria
     */
    public function __construct($criteria)
    {
        parent::__construct("Sorting criteria '" . $criteria . "'' is unsupported!");

        $this->criteria = $criteria;
    }

    /**
     * @return string
     */
    public function getCriteria()
    {
        return $this->criteria;
    }
}
