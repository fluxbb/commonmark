<?php

namespace FluxBB\CommonMark\Node\Block;

use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Node\NodeVisitorInterface;

class Blockquote extends Container
{

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->enterBlockquote($this);

        parent::visit($visitor);

        $visitor->leaveBlockquote($this);
    }

}
