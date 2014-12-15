<?php

namespace FluxBB\Markdown\Node;

class Link extends Node implements NodeAcceptorInterface
{

    protected $href;

    /**
     * @var string
     */
    protected $content;


    public function __construct($href, $content)
    {
        $this->href = $href;
        $this->content = $content;
    }

    public function getHref()
    {
        return $this->href;
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
        $visitor->visitLink($this);
    }

}
