<?php

namespace FluxBB\CommonMark\Node;

class Blockquote extends Container
{

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->enterBlockquote($this);

        parent::visit($visitor);

        $visitor->leaveBlockquote($this);
    }

}
