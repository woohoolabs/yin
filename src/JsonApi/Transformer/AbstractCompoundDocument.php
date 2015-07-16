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
     * Set the value of the "data" and "included" properties based on the "resource" property.
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     */
    abstract protected function setContent(Criteria $criteria);

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @param int $responseCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse(ResponseInterface $response, $resource, Criteria $criteria, $responseCode = 200)
    {
        $this->resource = $resource;
        $this->included = new Included();

        $response->getBody()->write(json_encode($this->transformContent($criteria)));
        $response = $response->withStatus($responseCode);
        $response = $response->withAddedHeader("Content-Type", $this->getContentType());

        return $response;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     * @return array
     */
    protected function transformContent(Criteria $criteria)
    {
        $content = $this->transformBaseContent();

        // DATA
        $this->setContent($criteria);
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
