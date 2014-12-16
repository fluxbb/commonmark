<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\BlankLine;
use FluxBB\Markdown\Node\Paragraph;

class ParagraphParser extends AbstractParser
{

    /**
     * Parse the given block content.
     *
     * Any newly created nodes should be pushed to the stack. Any remaining content should be passed to the next parser
     * in the chain.
     *
     * @param Text $block
     * @return void
     */
    public function parseBlock(Text $block)
    {
        $block->handle(
            '/^(.*)$/m',
            function (Text $line) {
                if ($line->copy()->trim()->isEmpty()) {
                    $this->stack->acceptBlankLine(new BlankLine($line));
                } else {
                    $this->stack->acceptParagraph(new Paragraph($line));
                }
            },
            function(Text $part) {
                $this->next->parseBlock($part);
            }
        );
    }

}
