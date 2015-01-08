<?php

namespace FluxBB\CommonMark\Node;

class ListBlock extends Container implements NodeAcceptorInterface
{

    protected $start;


    public function __construct($type, $start = null)
    {
        $this->type = $type;
        $this->start = $start;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getStart()
    {
        return $this->start ?: 1;
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
