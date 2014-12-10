<?php

namespace FluxBB\Markdown\Node;

class HorizontalRule extends Block implements NodeInterface, NodeAcceptorInterface
{

    public function getType()
    {
        return 'horizontal_rule';
    }

    public function canContain(Node $other)
    {
        return true;
    }

    public function proposeTo(NodeAcceptorInterface $block)
    {
        return $block->acceptHorizontalRule($this);
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->visitHorizontalRule($this);
    }

}
