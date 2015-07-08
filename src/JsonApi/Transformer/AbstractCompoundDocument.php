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
    protected $data;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\Included
     */
    protected $included;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     */
    public function __construct(ResponseInterface $response, Criteria $criteria)
    {
        parent::__construct($response, $criteria);
        $this->included = new Included();
    }

    abstract protected function setData();

    /**
     * @return array
     */
    protected function transformContent()
    {
        $content = parent::transformContent();

        // DATA
        $this->setData();
        $this->addOptionalTransformedCollectionToArray($content, "data", $this->data);
        $this->addOptionalTransformedItemToArray($content, "included", $this->included);

        return $content;
    }
}
