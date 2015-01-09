<?php

namespace FluxBB\CommonMark\Node;

use FluxBB\CommonMark\Common\Collection;
use FluxBB\CommonMark\Common\Text;

class Paragraph extends Node
{

    /**
     * @var Collection
     */
    protected $lines;


    public function __construct(Text $text)
    {
        $this->lines = $this->makeLines($text);
    }

    public function getText()
    {
        return new Text($this->lines->join("\n"));
    }

    public function spansMultipleLines()
    {
        return count($this->lines) > 0;
    }

    protected function makeLines(Text $text)
    {
        return $text->trim()->split('/\n/')->apply(function (Text $line) {
            return $line->copy()->ltrim();
        });
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->enterParagraph($this);

        $visitor->leaveParagraph($this);
    }

}
