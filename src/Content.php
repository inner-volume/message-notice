<?php

namespace Openphp\MessageNotice;

class Content
{
    /**
     * @var string|array
     */
    protected $at = '';
    /**
     * @var string
     */
    protected $content = '';
    /**
     * @var string
     */
    protected $pipeline = '';

    /**
     * @return array|string
     */
    public function getAt()
    {
        return $this->at;
    }

    /**
     * @param array|string $at
     * @return $this
     */
    public function setAt($at = '')
    {
        $this->at = $at;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getPipeline()
    {
        return $this->pipeline;
    }

    /**
     * @param string $pipeline
     * @return $this
     */
    public function setPipeline(string $pipeline)
    {
        $this->pipeline = $pipeline;
        return $this;
    }
}