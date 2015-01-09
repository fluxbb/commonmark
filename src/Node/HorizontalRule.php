<?php

namespace FluxBB\CommonMark\Node;

class HorizontalRule extends Node
{

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->visitHorizontalRule($this);
    }

}
