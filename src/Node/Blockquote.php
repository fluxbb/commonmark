<?php

namespace FluxBB\Markdown\Node;

class Blockquote extends Block implements NodeInterface, NodeAcceptorInterface
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

    public function proposeTo(NodeAcceptorInterface $block)
    {
        return $block->acceptBlockquote($this);
    }

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

}
