<?php

namespace FluxBB\CommonMark\Node;

class Emphasis extends Node implements NodeAcceptorInterface
{

    /**
     * @var string
     */
    protected $content;


    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
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
        $visitor->visitEmphasis($this);
    }

}