<?php

namespace FluxBB\CommonMark\Node;

use FluxBB\CommonMark\Common\Text;

class Code extends Node implements NodeAcceptorInterface
{

    protected $content;


    public function __construct(Text $content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    /**
     * Accept a visit from a node visitor.
     *
     * This method should instrument the visitor to handle this node correctly, and also pass it on to any child nodes.
     *
     * @param NodeVisitorInterface $visitor
     * @return void
     */
    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->visitCode($this);
    }

}
