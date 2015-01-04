<?php

namespace FluxBB\CommonMark\Node;

class HorizontalRule extends Node implements NodeAcceptorInterface
{

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->visitHorizontalRule($this);
    }

}
