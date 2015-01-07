<?php

namespace FluxBB\CommonMark\Node;

class ListBlock extends Container implements NodeAcceptorInterface
{

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function acceptParagraph(Paragraph $paragraph)
    {
        $this->addChild($paragraph);

        return $paragraph;
    }

    public function acceptBlockquote(Blockquote $blockquote)
    {
        return $this->parent->acceptBlockquote($blockquote);
    }

    public function acceptBlankLine(BlankLine $blankLine)
    {
        return $this->parent;
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->enterListBlock($this);

        parent::visit($visitor);

        $visitor->leaveListBlock($this);
    }

}
