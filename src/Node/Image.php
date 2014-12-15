<?php

namespace FluxBB\Markdown\Node;

class Image extends Node implements NodeAcceptorInterface
{

    protected $url;


    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
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
        $visitor->visitImage($this);
    }

}
