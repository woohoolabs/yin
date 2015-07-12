<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Schema\Included;

abstract class AbstractCompoundDocument extends AbstractDocument
{
    /**
     * @var mixed
     */
    protected $resource;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\Included
     */
    protected $included;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param mixed $resource
     */
    public function __construct(ResponseInterface $response, $resource)
    {
        parent::__construct($response);
        $this->included = new Included();
    }

    /**
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     */
    abstract protected function setContent($resource, Criteria $criteria);

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @return array
     */
    protected function transformContent(Criteria $criteria)
    {
        $content = parent::transformContent($criteria);

        // DATA
        $this->setContent($this->resource, $criteria);
        $content["data"] = $this->data;

        // INCLUDED
        if ($this->included !== null) {
            $content["included"] = $this->included->transform($this->resource, $criteria);
        }

        return $content;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Included
     */
    public function getIncluded()
    {
        return $this->included;
    }
}
