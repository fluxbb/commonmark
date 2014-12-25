<?php

namespace FluxBB\CommonMark\Node;

use FluxBB\CommonMark\Common\Text;

class CodeBlock extends Node implements NodeAcceptorInterface
{

    /**
     * @var Text
     */
    protected $content;

    /**
     * @var string
     */
    protected $language;


    public function __construct(Text $text, $language = '')
    {
        $this->content = $text;
        $this->language = $language;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function hasLanguage()
    {
        return ! empty($this->language);
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
