<?php

namespace FluxBB\Markdown\Node;

use FluxBB\Markdown\Common\Collection;
use FluxBB\Markdown\Common\Text;

class Paragraph extends LeafBlock implements NodeAcceptorInterface
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
        return $this->lines->apply(function (Text $line) {
            return $line->trim();
        })->join("\n");
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

    public function acceptCodeBlock(CodeBlock $codeBlock)
    {
        $codeBlock->getLines()->each(function (Text $line) {
            $this->lines->add($line->prepend('    '));
        });

        return $this;
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->enterParagraph($this);

        $visitor->leaveParagraph($this);
    }

}
