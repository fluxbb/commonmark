<?php

namespace FluxBB\Markdown\Node;

use FluxBB\Markdown\Common\Collection;
use FluxBB\Markdown\Common\Text;

class Paragraph extends Block implements NodeInterface, NodeAcceptorInterface
{

    /**
     * @var Collection
     */
    protected $lines;


    public function __construct(Text $text)
    {
        $this->lines = new Collection([$text]);
    }

    public function getType()
    {
        return 'paragraph';
    }

    public function toString()
    {
        return parent::toString() . '("' . $this->lines->join(' ') . '")';
    }

    public function canContain(Node $other)
    {
        return true;
    }

    public function getText()
    {
        return $this->lines->apply(function (Text $line) {
            return $line->trim();
        })->join("\n");
    }

    public function proposeTo(NodeAcceptorInterface $block)
    {
        return $block->acceptParagraph($this);
    }

    public function acceptParagraph(Paragraph $paragraph)
    {
        $paragraph->lines->each(function (Text $line) {
            $this->lines->add($line);
        });

        return $this;
    }

    public function acceptBlankLine(BlankLine $blankLine)
    {
        $this->close();

        return $this->parent;
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->enterParagraph($this);

        $visitor->leaveParagraph($this);
    }

}
