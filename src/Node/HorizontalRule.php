<?php

namespace FluxBB\Markdown\Node;

class HorizontalRule extends LeafBlock implements NodeAcceptorInterface
{

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->visitHorizontalRule($this);
    }

}
