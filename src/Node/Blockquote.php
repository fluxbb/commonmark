<?php

namespace FluxBB\Markdown\Node;

class Blockquote extends Block implements NodeAcceptorInterface
{

    public function getType()
    {
        return 'block_quote';
    }

    public function canContain(Node $other)
    {
        return $other->getType() == 'paragraph';
    }

    public function accepts(Node $block)
    {
        return $block->getType() == 'paragraph';
    }

    public function acceptParagraph(Paragraph $paragraph)
    {
        $this->addChild($paragraph);

        return $paragraph;
    }

    public function acceptHeading(Heading $heading)
    {
        $this->addChild($heading);

        return $this;
    }

    public function acceptBlockquote(Blockquote $blockquote)
    {
        $this->merge($blockquote);

        return $this;
    }

    public function acceptBlankLine(BlankLine $blankLine)
    {
        $this->close();

        return $this->parent;
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->enterBlockquote($this);

        parent::visit($visitor);

        $visitor->leaveBlockquote($this);
    }
}
