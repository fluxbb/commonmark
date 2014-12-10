<?php

namespace FluxBB\Markdown\Node;

class Paragraph extends Block implements NodeInterface, NodeAcceptorInterface
{

    protected $text;


    public function __construct($text)
    {
        $this->text = $text;
    }

    public function getType()
    {
        return 'paragraph';
    }

    public function toString()
    {
        return parent::toString() . '("' . str_replace("\n", ' ', $this->text) . '")';
    }

    public function canContain(Node $other)
    {
        return true;
    }

    public function proposeTo(NodeAcceptorInterface $block)
    {
        return $block->acceptParagraph($this);
    }

    public function acceptParagraph(Paragraph $paragraph)
    {
        $this->text .= "\n" . $paragraph->text;

        return $this;
    }

}
