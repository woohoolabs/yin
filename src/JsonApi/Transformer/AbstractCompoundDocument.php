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
     * @var \WoohooLabs\Yin\JsonApi\Request\Criteria
     */
    protected $criteria;

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
     * @param int $statusCode
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     */
    public function __construct(ResponseInterface $response, $statusCode, $resource, Criteria $criteria)
    {
        parent::__construct($response, $statusCode);
        $this->criteria = $criteria;
        $this->included = new Included();
    }

    /**
     * @param mixed $resource
     */
    abstract protected function setContent($resource);

    /**
     * @return array
     */
    protected function transformContent()
    {
        $content = parent::transformContent();

        // DATA
        $this->setContent($this->resource);
        $content["data"] = $this->data;

        // INCLUDED
        if ($this->included !== null) {
            $content["included"] = $this->included->transform($this->resource, $this->criteria);
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
