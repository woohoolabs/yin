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

    abstract protected function headerChanged(string $name): void;

    abstract protected function queryParamChanged(string $name): void;

    public function __construct(ServerRequestInterface $request, DeserializerInterface $deserializer)
    {
        $this->serverRequest = $request;
        $this->deserializer = $deserializer;
    }

    public function getProtocolVersion(): string
    {
        return $this->serverRequest->getProtocolVersion();
    }

    /**
     * @param string $version HTTP protocol version
     * @return static
     */
    public function withProtocolVersion($version)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withProtocolVersion($version);

        return $self;
    }

    /**
     * @return string[][]
     */
    public function getHeaders(): array
    {
        return $this->serverRequest->getHeaders();
    }

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $name Case-insensitive header field name.
     */
    public function hasHeader($name): bool
    {
        return $this->serverRequest->hasHeader($name);
    }

    /**
     * @param string $name
     * @return string[]
     */
    public function getHeader($name): array
    {
        return $this->serverRequest->getHeader($name);
    }

    /**
     * @param string $name
     */
    public function getHeaderLine($name): string
    {
        return $this->serverRequest->getHeaderLine($name);
    }

    /**
     * @param string $name Case-insensitive header field name.
     * @param string|string[] $value Header value(s).
     * @return static
     */
    public function withHeader($name, $value)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withHeader($name, $value);
        $self->headerChanged($name);

        return $self;
    }

    /**
     * @param string $name Case-insensitive header field name to add.
     * @param string|string[] $value Header value(s).
     * @return static
     */
    public function withAddedHeader($name, $value)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withAddedHeader($name, $value);
        $self->headerChanged($name);

        return $self;
    }

    /**
     * @param string $name
     * @return static
     */
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

    /**
     * @return static
     */
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

    /**
     * @param mixed $requestTarget
     * @return static
     */
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

    /**
     * @param string $method Case-sensitive method.
     * @return static
     */
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

    /**
     * @param bool $preserveHost Preserve the original state of the Host header.
     * @return static
     */
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

    /**
     * @return static
     */
    public function withCookieParams(array $cookies)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withCookieParams($cookies);

        return $self;
    }

    /**
     * @return array<string, mixed>
     */
    public function getQueryParams(): array
    {
        return $this->serverRequest->getQueryParams();
    }

    /**
     * @return static
     */
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

    /**
     * @return static
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withUploadedFiles($uploadedFiles);

        return $self;
    }

    /**
     * @return array|object|null
     */
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

    /**
     * @param array|object|null $data
     * @return static
     */
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

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return $this->serverRequest->getAttribute($name, $default);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return static
     */
    public function withAttribute($name, $value)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withAttribute($name, $value);

        return $self;
    }

    /**
     * @param string $name The attribute name.
     * @return static
     */
    public function withoutAttribute($name)
    {
        $self = clone $this;
        $self->serverRequest = $this->serverRequest->withoutAttribute($name);

        return $self;
    }
}
