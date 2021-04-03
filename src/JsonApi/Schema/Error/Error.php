<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Error;

use WoohooLabs\Yin\JsonApi\Schema\Link\ErrorLinks;
use WoohooLabs\Yin\JsonApi\Schema\MetaTrait;

class Error
{
    use MetaTrait;

    protected string $id = "";
    protected ?ErrorLinks $links = null;
    protected string $status = "";
    protected string $code = "";
    protected string $title = "";
    protected string $detail = "";
    protected ?ErrorSource $source = null;

    public static function create(): Error
    {
        return new Error();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Error
    {
        $this->id = $id;

        return $this;
    }

    public function getLinks(): ?ErrorLinks
    {
        return $this->links;
    }

    /**
     * @return $this
     */
    public function setLinks(ErrorLinks $links): Error
    {
        $this->links = $links;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): Error
    {
        $this->status = $status;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): Error
    {
        $this->code = $code;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Error
    {
        $this->title = $title;

        return $this;
    }

    public function getDetail(): string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): Error
    {
        $this->detail = $detail;

        return $this;
    }

    public function getSource(): ?ErrorSource
    {
        return $this->source;
    }

    public function setSource(ErrorSource $source): Error
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @internal
     */
    public function transform(): array
    {
        $content = [];

        $this->transformId($content);
        $this->transformMeta($content);
        $this->transformLinks($content);
        $this->transformStatus($content);
        $this->transformCode($content);
        $this->transformTitle($content);
        $this->transformDetail($content);
        $this->transformSource($content);

        return $content;
    }

    protected function transformId(array &$content): void
    {
        if ($this->id !== "") {
            $content["id"] = $this->id;
        }
    }

    protected function transformMeta(array &$content): void
    {
        if (empty($this->meta) === false) {
            $content["meta"] = $this->meta;
        }
    }

    protected function transformLinks(array &$content): void
    {
        if ($this->links !== null) {
            $content["links"] = $this->links->transform();
        }
    }

    protected function transformStatus(array &$content): void
    {
        if ($this->status !== "") {
            $content["status"] = $this->status;
        }
    }

    protected function transformCode(array &$content): void
    {
        if ($this->code !== "") {
            $content["code"] = $this->code;
        }
    }

    protected function transformTitle(array &$content): void
    {
        if ($this->title !== "") {
            $content["title"] = $this->title;
        }
    }

    protected function transformDetail(array &$content): void
    {
        if ($this->detail !== "") {
            $content["detail"] = $this->detail;
        }
    }

    protected function transformSource(array &$content): void
    {
        if ($this->source !== null) {
            $content["source"] = $this->source->transform();
        }
    }
}
