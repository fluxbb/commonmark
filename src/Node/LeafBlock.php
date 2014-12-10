<?php

namespace FluxBB\Markdown\Node;

abstract class LeafBlock extends Node implements NodeAcceptorInterface
{

    public function canContain(Node $other)
    {
        return false;
    }

    public function accept(NodeInterface $node)
    {
        return $node->proposeTo($this);
    }

    public function acceptParagraph(Paragraph $paragraph)
    {
        return $this->parent->acceptParagraph($paragraph);
    }

    public function acceptBlockquote(Blockquote $blockquote)
    {
        return $this->parent->acceptBlockquote($blockquote);
    }

}
