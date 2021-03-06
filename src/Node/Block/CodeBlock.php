<?php

namespace FluxBB\CommonMark\Node\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Node;
use FluxBB\CommonMark\Node\NodeVisitorInterface;

class CodeBlock extends Node
{

    /**
     * @var Text
     */
    protected $content;

    /**
     * @var Text
     */
    protected $language;


    public function __construct(Text $text, Text $language = null)
    {
        $this->content = $text;
        $this->language = $language ?: new Text();
    }

    public function getContent()
    {
        return $this->content;
    }

    public function hasLanguage()
    {
        return ! $this->language->isEmpty();
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->visitCodeBlock($this);
    }

}
