<?php

namespace FluxBB\CommonMark\Node;

class HardBreak extends Node implements NodeAcceptorInterface
{

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
        $visitor->visitHardBreak($this);
    }

}
