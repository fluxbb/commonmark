<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\BlankLine;
use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Node\Paragraph;
use FluxBB\CommonMark\Parser\AbstractBlockParser;

class ParagraphParser extends AbstractBlockParser
{

    /**
     * Parse the given content.
     *
     * Any newly created nodes should be pushed to the stack. Any remaining content should be passed to the next parser
     * in the chain.
     *
     * @param Text $content
     * @param Container $target
     * @return void
     */
    public function parseBlock(Text $content, Container $target)
    {
        $content->handle(
            '/^(.*)$/m',
            function (Text $line) use ($target) {
                if ($line->copy()->trim()->isEmpty()) {
                    $target->acceptBlankLine(new BlankLine($line));
                } else {
                    $target->acceptParagraph(new Paragraph($line));
                }
            },
            function (Text $part) use ($target) {
                $this->next->parseBlock($part, $target);
            }
        );
    }

}
