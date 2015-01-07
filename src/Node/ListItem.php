<?php

namespace FluxBB\CommonMark\Node;

use FluxBB\CommonMark\Common\Text;

class ListItem extends Container
{

    protected $content;


    public function __construct(Text $content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

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
