<?php

namespace FluxBB\Markdown\Node;

use FluxBB\Markdown\Common\Text;

class Paragraph extends Block implements NodeInterface, NodeAcceptorInterface
{

    /**
     * @var Text
     */
    protected $text;


    public function __construct(Text $text)
    {
        $this->text = $text;
    }

    public function getType()
    {
        return 'paragraph';
    }

    public function toString()
    {
        return parent::toString() . '("' . $this->text->replaceString("\n", ' ') . '")';
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
        $this->text->append("\n")->append($paragraph->text);

        return $this;
    }

}
