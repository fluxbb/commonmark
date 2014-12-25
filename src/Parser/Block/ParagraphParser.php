<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\BlankLine;
use FluxBB\CommonMark\Node\Paragraph;
use FluxBB\CommonMark\Parser\AbstractParser;

class ParagraphParser extends AbstractParser
{

    /**
     * Parse the given content.
     *
     * Any newly created nodes should be pushed to the stack. Any remaining content should be passed to the next parser
     * in the chain.
     *
     * @param Text $content
     * @return void
     */
    public function parse(Text $content)
    {
        $content->handle(
            '/^(.*)$/m',
            function (Text $line) {
                if ($line->copy()->trim()->isEmpty()) {
                    $this->stack->acceptBlankLine(new BlankLine($line));
                } else {
                    $this->stack->acceptParagraph(new Paragraph($line));
                }
            },
            function(Text $part) {
                $this->next->parse($part);
            }
        );
    }

}
