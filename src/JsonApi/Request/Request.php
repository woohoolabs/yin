<?php
namespace WoohooLabs\Yin\JsonApi\Request;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements RequestInterface
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    private $serverRequest;

    /**
     * @var array
     */
    private $includedFields = [];

    /**
     * @var array
     */
    private $includedRelationships = [];

    /**
     * @var array
     */
    private $sorting = [];

    /**
     * @var array
     */
    private $pagination = [];

    /**
     * @var array
     */
    private $filtering = [];

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->serverRequest = $request->withParsedBody(json_decode($request->getBody(), true));

        $this->setIncludedRelationships();
        $this->setIncludedFields();
        $this->setSorting();
    }

    protected function setIncludedFields()
    {
        foreach ($this->getQueryParam("fields", []) as $resourceType => $fields) {
            $this->includedFields[$resourceType] = array_flip(explode(",", $fields));
        }
    }

    /**
     * @param string $resourceType
     * @return array
     */
    public function getIncludedFields($resourceType)
    {
        return isset($this->includedFields[$resourceType]) ? array_keys($this->includedFields[$resourceType]) : [];
    }

    /**
     * @param string $resourceType
     * @param string $field
     * @return bool
     */
    public function isIncludedField($resourceType, $field)
    {
        return isset($this->includedFields[$resourceType][$field]);
    }

    /**
     * @return array
     */
    protected function setIncludedRelationships()
    {
        $relationshipNames = explode(",", $this->getQueryParam("include", ""));
        foreach ($relationshipNames as $relationship) {
            $relationship = ".$relationship.";
            $length = strlen($relationship);
            $dot1 = 0;

            while ($dot1 < $length - 1) {
                $dot2 = strpos($relationship, ".", $dot1 + 1);
                $path = substr($relationship, 1, $dot1 > 0 ? $dot1 - 1 : 0);
                $name = substr($relationship, $dot1 + 1, $dot2 - $dot1 - 1);

                if (isset($this->includedRelationships[$path]) === false) {
                    $this->includedRelationships[$path] = [];
                }
                $this->includedRelationships[$path][$name] = $name;

                $dot1 = $dot2;
            };
        }
    }

    /**
     * @param string $baseRelationshipPath
     * @return array
     */
    public function getIncludedRelationships($baseRelationshipPath)
    {
        if (isset($this->includedRelationships[$baseRelationshipPath])) {
            return $this->includedRelationships[$baseRelationshipPath];
        } else {
            return [];
        }
    }

    /**
     * @param string $baseRelationshipPath
     * @param string $relationshipName
     * @return bool
     */
    public function isIncludedRelationship($baseRelationshipPath, $relationshipName)
    {
        return isset($this->includedRelationships[$baseRelationshipPath][$relationshipName]);
    }

    protected function setSorting()
    {
        $this->sorting = explode(",", $this->getQueryParam("sort", ""));
    }

    /**
     * @return array
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    protected function setPagination()
    {
        $this->pagination = $this->getQueryParam("page", null);
    }

    /**
     * @return array|null
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    protected function setFiltering()
    {
        $this->filtering = $this->getQueryParam("filter", null);
    }

    /**
     * @return array
     */
    public function getFiltering()
    {
        return $this->filtering;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return array|string|null
     */
    public function getQueryParam($name, $default = null)
    {
        $queryParams = $this->serverRequest->getQueryParams();

        return isset($queryParams[$name]) ? $queryParams[$name] : $default;
    }

    /**
     * @return array|null
     */
    public function getBodyData()
    {
        $body = $this->serverRequest->getParsedBody();
        return isset($body["data"])? $body["data"] : null;
    }

    /**
     * @return string|null
     */
    public function getBodyDataType()
    {
        $data = $this->getBodyData();

        return isset($data["type"]) ? $data["type"] : null;
    }

    /**
     * @return string|null
     */
    public function getBodyDataId()
    {
        $data = $this->getBodyData();

        return isset($data["id"]) ? $data["id"] : null;
    }

    /**
     * @inheritDoc
     */
    public function getProtocolVersion()
    {
        return $this->serverRequest->getProtocolVersion();
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion($version)
    {
        return $this->serverRequest->withProtocolVersion($version);
    }

    /**
     * @inheritDoc
     */
    public function getHeaders()
    {
        return $this->serverRequest->getHeaders();
    }

    /**
     * @inheritDoc
     */
    public function hasHeader($name)
    {
        return $this->serverRequest->hasHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name)
    {
        return $this->serverRequest->getHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($name)
    {
        return $this->serverRequest->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value)
    {
        return $this->serverRequest->withHeader($name, $value);
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($name, $value)
    {
        return $this->serverRequest->withAddedHeader($name, $value);
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($name)
    {
        return $this->serverRequest->withoutHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        return $this->serverRequest->getBody();
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body)
    {
        return $this->serverRequest->withBody($body);
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget()
    {
        return $this->serverRequest->getRequestTarget();
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget($requestTarget)
    {
        return $this->serverRequest->withRequestTarget($requestTarget);
    }

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return $this->serverRequest->getMethod();
    }

    /**
     * @inheritDoc
     */
    public function withMethod($method)
    {
        return $this->serverRequest->withMethod($method);
    }

    /**
     * @inheritDoc
     */
    public function getUri()
    {
        return $this->serverRequest->getUri();
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        return $this->serverRequest->withUri($uri, $preserveHost);
    }

    /**
     * @inheritDoc
     */
    public function getServerParams()
    {
        return $this->serverRequest->getServerParams();
    }

    /**
     * @inheritDoc
     */
    public function getCookieParams()
    {
        return $this->serverRequest->getCookieParams();
    }

    /**
     * @inheritDoc
     */
    public function withCookieParams(array $cookies)
    {
        return $this->serverRequest->withCookieParams($cookies);
    }

    /**
     * @inheritDoc
     */
    public function getQueryParams()
    {
        return $this->serverRequest->getQueryParams();
    }

    /**
     * @inheritDoc
     */
    public function withQueryParams(array $query)
    {
        return $this->serverRequest->withQueryParams($query);
    }

    /**
     * @inheritDoc
     */
    public function getUploadedFiles()
    {
        return $this->serverRequest->getUploadedFiles();
    }

    /**
     * @inheritDoc
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        return $this->serverRequest->withUploadedFiles($uploadedFiles);
    }

    /**
     * @inheritDoc
     */
    public function getParsedBody()
    {
        return $this->serverRequest->getParsedBody();
    }

    /**
     * @inheritDoc
     */
    public function withParsedBody($data)
    {
        return $this->serverRequest->withParsedBody($data);
    }

    /**
     * @inheritDoc
     */
    public function getAttributes()
    {
        return $this->serverRequest->getAttributes();
    }

    /**
     * @inheritDoc
     */
    public function getAttribute($name, $default = null)
    {
        return $this->serverRequest->getAttribute($name, $default);
    }

    /**
     * @inheritDoc
     */
    public function withAttribute($name, $value)
    {
        return $this->serverRequest->withAttribute($name, $value);
    }

    /**
     * @inheritDoc
     */
    public function withoutAttribute($name)
    {
        return $this->serverRequest->withoutAttribute($name);
    }
}
