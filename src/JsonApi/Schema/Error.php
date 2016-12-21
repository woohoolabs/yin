<?php
namespace WoohooLabs\Yin\JsonApi\Schema;

class Error
{
    use MetaTrait;
    use LinksTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $detail;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\ErrorSource
     */
    protected $source;

    /**
     * @return $this
     */
    public static function create()
    {
        return new self();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @param string $detail
     * @return $this
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
        return $this;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\ErrorSource
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\ErrorSource $source
     * @return $this
     */
    public function setSource(ErrorSource $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return array
     */
    public function transform()
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

    protected function transformId(array &$content)
    {
        if ($this->id) {
            $content["id"] = $this->id;
        }
    }

    protected function transformMeta(array &$content)
    {
        if (empty($this->meta) === false) {
            $content["meta"] = $this->meta;
        }
    }

    protected function transformLinks(array &$content)
    {
        if ($this->links) {
            $content["links"] = $this->links->transform();
        }
    }

    protected function transformStatus(array &$content)
    {
        if ($this->status) {
            $content["status"] = $this->status;
        }
    }

    protected function transformCode(array &$content)
    {
        if ($this->code) {
            $content["code"] = $this->code;
        }
    }

    protected function transformTitle(array &$content)
    {
        if ($this->title) {
            $content["title"] = $this->title;
        }
    }

    protected function transformDetail(array &$content)
    {
        if ($this->detail) {
            $content["detail"] = $this->detail;
        }
    }

    protected function transformSource(array &$content)
    {
        if ($this->source) {
            $content["source"] = $this->source->transform();
        }
    }
}
