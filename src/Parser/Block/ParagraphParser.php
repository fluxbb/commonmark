<?php

namespace FluxBB\Markdown\Parser\Block;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\BlankLine;
use FluxBB\Markdown\Node\Paragraph;
use FluxBB\Markdown\Parser\AbstractParser;

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
