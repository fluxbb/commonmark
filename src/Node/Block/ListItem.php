<?php

namespace FluxBB\CommonMark\Node\Block;

use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Node\NodeVisitorInterface;

class ListItem extends Container
{

    protected $isTerse = false;

    protected $content;


    public function shouldBeTerse()
    {
        return (count($this->children) == 1) &&
               ($this->children[0] instanceof Paragraph) &&
               $this->children[0]->spansMultipleLines();
    }

    public function terse()
    {
        $this->isTerse = true;

        $this->content = $this->children[0]->getText();
        $this->children = [];
    }

    public function isTerse()
    {
        return $this->isTerse;
    }

    public function getContent()
    {
        return $this->content;
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
