<?php

namespace FluxBB\Markdown\Node;

class ListItem extends Container
{

    public function acceptListBlock(ListBlock $listBlock)
    {
        return $this->parent->acceptListBlock($listBlock);
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
        $visitor->enterListItem($this);

        parent::visit($visitor);

        $visitor->leaveListItem($this);
    }
}
