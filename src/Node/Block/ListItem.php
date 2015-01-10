<?php

namespace FluxBB\CommonMark\Node\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Node\Inline\String;
use FluxBB\CommonMark\Node\NodeVisitorInterface;

class ListItem extends Container
{

    protected $isTerse = false;

    protected $content;


    public function shouldBeTerse()
    {
        return count($this->children) == 0 || $this->firstParagraphIsTerse();
    }

    protected function firstParagraphIsTerse()
    {
        $firstChild = $this->children[0];

        return ($firstChild instanceof Paragraph) && (! $firstChild->spansMultipleLines());
    }

    public function terse()
    {
        $text = new Text();
        if (isset($this->children[0])) {
            $paragraph = array_shift($this->children);
            $text = $paragraph->getText();

            if (count($this->children) > 0) {
                $text->append("\n");
            }
        }

        $this->isTerse = true;
        array_unshift($this->children, new String($text));
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
