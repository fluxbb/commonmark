<?php

namespace FluxBB\CommonMark\Node;

use FluxBB\CommonMark\Common\Text;

class BlankLine extends Node implements NodeAcceptorInterface
{

    /**
     * @var Text
     */
    protected $content;


    public function __construct(Text $content)
    {
        $this->content = $content;
    }

    /**
     * @return Text
     */
    public function getContent()
    {
        return $this->content->copy();
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
        //
    }

}
