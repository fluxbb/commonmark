<?php

namespace FluxBB\CommonMark\Node\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Node\Node;
use FluxBB\CommonMark\Node\NodeVisitorInterface;

class ListItem extends Container
{

    protected $terse = false;

    protected $content;


    public function terse()
    {
        $this->terse = true;
    }

    public function isTerse()
    {
        return $this->terse;
    }

    public function getContent()
    {
        return $this->content ?: new Text();
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
