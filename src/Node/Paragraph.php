<?php

namespace FluxBB\CommonMark\Node;

use FluxBB\CommonMark\Common\Collection;
use FluxBB\CommonMark\Common\Text;

class Paragraph extends Node implements NodeAcceptorInterface
{

    /**
     * @var Collection
     */
    protected $lines;


    public function __construct(Text $text)
    {
        $this->lines = new Collection([$text]);
    }

    public function getText()
    {
        return (new Text($this->lines->apply(function (Text $line) {
            return $line->copy()->ltrim();
        })->join("\n")))->trim();
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
        return $this->parent;
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->enterParagraph($this);

        $visitor->leaveParagraph($this);
    }

}
