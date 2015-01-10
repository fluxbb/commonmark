<?php

namespace FluxBB\CommonMark\Node\Block;

use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Node\Inline\String;
use FluxBB\CommonMark\Node\NodeVisitorInterface;

class ListItem extends Container
{

    protected $terse = false;


    public function terse()
    {
        $this->terse = true;

        for ($i = 0; $i < count($this->children); $i++) {
            $node = $this->children[$i];

            if ($node instanceof Paragraph) {
                // Ensure the line ends with a newline
                $content = $node->getText()->rtrim("\n")->append("\n");
                $this->children[$i] = new String($content);
            }
        }
    }

    public function isTerse()
    {
        return $this->terse;
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
