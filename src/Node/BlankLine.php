<?php

namespace FluxBB\Markdown\Node;

class BlankLine extends Block implements NodeInterface, NodeAcceptorInterface
{

    public function getType()
    {
        return 'blank_line';
    }

    public function canContain(Node $other)
    {
        return true;
    }

    public function proposeTo(NodeAcceptorInterface $block)
    {
        return $block->acceptBlankLine($this);
    }

}
