<?php

namespace FluxBB\CommonMark\Node;

use FluxBB\CommonMark\Common\Text;

class HTMLBlock extends Node
{

    /**
     * @var Text
     */
    protected $content;


    public function __construct(Text $content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->visitHTMLBlock($this);
    }
}
