<?php

namespace FluxBB\CommonMark\Node\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Node\Node;
use FluxBB\CommonMark\Node\NodeVisitorInterface;

class ListItem extends Container
{

    protected $isTerse = false;

    protected $content;


    public function shouldBeTerse()
    {
        return count($this->children) == 0 ||
               $this->firstParagraphIsTerse();
    }

    protected function firstParagraphIsTerse()
    {
        $firstChild = $this->children[0];

        return $this->paragraphIsTerse($firstChild) &&
               (count($this->children) == 1 || $this->secondChildIsList());
    }

    protected function secondChildIsList()
    {
        return $this->children[1] instanceof ListBlock;
    }

    protected function paragraphIsTerse(Node $node)
    {
        return ($node instanceof Paragraph) && ! $node->spansMultipleLines();
    }

    public function terse()
    {
        if (isset($this->children[0])) {
            $paragraph = array_shift($this->children);
            $this->content = $paragraph->getText();
        }

        $this->isTerse = true;
    }

    public function isTerse()
    {
        return $this->isTerse;
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
