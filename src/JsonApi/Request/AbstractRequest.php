<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Request;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use WoohooLabs\Yin\JsonApi\Serializer\DeserializerInterface;

abstract class AbstractRequest
{
    /**
     * @var ServerRequestInterface
     */
    protected $serverRequest;

    /**
     * @var DeserializerInterface
     */
    protected $deserializer;

    /**
     * @var bool
     */
    protected $isParsed = false;

    abstract protected function headerChanged(string $name);

    abstract protected function queryParamChanged(string $name);

    public function __construct(ServerRequestInterface $request, DeserializerInterface $deserializer)
    {
        $this->serverRequest = $request;
        $this->deserializer = $deserializer;
    }

    public function getProtocolVersion(): string
    {
        return $this->serverRequest->getProtocolVersion();
    }

    public function withProtocolVersion($version)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withProtocolVersion($version);

        return $self;
    }

    public function getHeaders(): array
    {
        return $this->serverRequest->getHeaders();
    }

    public function hasHeader($name): bool
    {
        return $this->serverRequest->hasHeader($name);
    }

    public function getHeader($name): array
    {
        return $this->serverRequest->getHeader($name);
    }

    public function getHeaderLine($name): string
    {
        return $this->serverRequest->getHeaderLine($name);
    }

    public function withHeader($name, $value)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withHeader($name, $value);
        $self->headerChanged($name);

        return $self;
    }

    public function withAddedHeader($name, $value)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withAddedHeader($name, $value);
        $self->headerChanged($name);

        return $self;
    }

    public function withoutHeader($name)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withoutHeader($name);
        $self->headerChanged($name);

        return $self;
    }

    public function getBody(): StreamInterface
    {
        return $this->serverRequest->getBody();
    }

    public function withBody(StreamInterface $body)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withBody($body);

        return $self;
    }

    public function getRequestTarget(): string
    {
        return $this->serverRequest->getRequestTarget();
    }

    public function withRequestTarget($requestTarget)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withRequestTarget($requestTarget);

        return $self;
    }

    public function getMethod(): string
    {
        return $this->serverRequest->getMethod();
    }

    public function withMethod($method)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withMethod($method);

        return $self;
    }

    public function getUri(): UriInterface
    {
        return $this->serverRequest->getUri();
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withUri($uri, $preserveHost);

        return $self;
    }

    public function getServerParams(): array
    {
        return $this->serverRequest->getServerParams();
    }

    public function getCookieParams(): array
    {
        return $this->serverRequest->getCookieParams();
    }

    public function withCookieParams(array $cookies)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withCookieParams($cookies);

        return $self;
    }

    public function getQueryParams(): array
    {
        return $this->serverRequest->getQueryParams();
    }

    public function withQueryParams(array $query)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withQueryParams($query);

        foreach ($query as $name => $value) {
            $self->queryParamChanged($name);
        }

        return $self;
    }

    /**
     * @param mixed $default
     * @return array|string|mixed
     */
    public function getQueryParam(string $name, $default = null)
    {
        $queryParams = $this->serverRequest->getQueryParams();

        return $queryParams[$name] ?? $default;
    }

    /**
     * Returns a new request with the "$name" query parameter.
     *
     * @param mixed $value
     * @return $this
     */
    public function withQueryParam(string $name, $value)
    {
        $self = clone $this;
        $queryParams = $this->serverRequest->getQueryParams();
        $queryParams[$name] = $value;
        $self->serverRequest = $this->serverRequest->withQueryParams($queryParams);
        $self->queryParamChanged($name);

        return $self;
    }

    public function getUploadedFiles(): array
    {
        return $this->serverRequest->getUploadedFiles();
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withUploadedFiles($uploadedFiles);

        return $self;
    }

    public function getParsedBody()
    {
        if ($this->isParsed === false) {
            $parsedBody = $this->serverRequest->getParsedBody();
            if ($parsedBody === null || $parsedBody === []) {
                $parsedBody = $this->deserializer->deserialize($this->serverRequest);
                $this->serverRequest = $this->serverRequest->withParsedBody($parsedBody);
                $this->isParsed = true;
            }
        }

        return $this->serverRequest->getParsedBody();
    }

    public function withParsedBody($data)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withParsedBody($data);
        $this->isParsed = true;

        return $self;
    }

    public function getAttributes(): array
    {
        return $this->serverRequest->getAttributes();
    }

    public function getAttribute($name, $default = null)
    {
        return $this->serverRequest->getAttribute($name, $default);
    }

    public function withAttribute($name, $value)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withAttribute($name, $value);

        return $self;
    }

    public function withoutAttribute($name)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withoutAttribute($name);

        return $self;
    }
}
