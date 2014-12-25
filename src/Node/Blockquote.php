<?php

namespace FluxBB\CommonMark\Node;

class Blockquote extends Container implements NodeAcceptorInterface
{

    public function acceptParagraph(Paragraph $paragraph)
    {
        $this->addChild($paragraph);

        return $paragraph;
    }

    public function acceptBlockquote(Blockquote $blockquote)
    {
        $this->merge($blockquote);

        return $this;
    }

    public function acceptBlankLine(BlankLine $blankLine)
    {
        return $this->parent;
    }

    public function acceptHorizontalRule(HorizontalRule $horizontalRule)
    {
        return $this->parent->acceptHorizontalRule($horizontalRule);
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->enterBlockquote($this);

        parent::visit($visitor);

        $visitor->leaveBlockquote($this);
    }
}
