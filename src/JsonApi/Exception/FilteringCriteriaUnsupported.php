<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

class FilteringCriteriaUnsupported extends \Exception
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
        parent::__construct("Filtering criteria '" . $criteria . "'' is unsupported!");

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
