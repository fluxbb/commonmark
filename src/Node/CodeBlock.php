<?php

namespace FluxBB\Markdown\Node;

use FluxBB\Markdown\Common\Collection;
use FluxBB\Markdown\Common\Text;

class CodeBlock extends LeafBlock implements NodeAcceptorInterface
{

    /**
     * @var Collection
     */
    protected $lines;


    public function __construct(Text $text)
    {
        $this->lines = new Collection([$text->replace('/^[ ]{4}/', '')]);
    }

    public function acceptBlankLine(BlankLine $blankLine)
    {
        $this->lines->add($blankLine->getContent()->replace('/^[ ]{0,4}/', ''));

        return $this;
    }

    public function acceptCodeBlock(CodeBlock $codeBlock)
    {
        $codeBlock->lines->each(function (Text $line) {
            $this->lines->add($line);
        });

        return $this;
    }

    public function getContent()
    {
        $content = new Text($this->lines->join("\n"));

        // Just in case we added blank lines at the end, we remove them, and finally add back the trailing newline.
        $content->replace('/(\n[ ]*)*$/', '')->append("\n");

        return $content;
    }

    public function getLines()
    {
        return $this->lines;
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->visitCodeBlock($this);
    }

}
