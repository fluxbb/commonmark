<?php

namespace FluxBB\CommonMark\Node\Block;

use FluxBB\CommonMark\Node\Node;
use FluxBB\CommonMark\Node\NodeVisitorInterface;

class HorizontalRule extends Node
{

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->visitHorizontalRule($this);
    }

}
