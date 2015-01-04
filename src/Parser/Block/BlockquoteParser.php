<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Blockquote;
use FluxBB\CommonMark\Parser\AbstractBlockParser;

class BlockquoteParser extends AbstractBlockParser
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
    public function parseBlock(Text $content)
    {
        $content->handle(
            '/
                (?=[\A\n])
                (
                    [ ]{0,3}
                    \>
                    [ ]?
                    [^\n]+
                    (\n|$)
                )+
            /x',
            function (Text $content) {
                // Remove block quote marker plus surrounding whitespace on each line
                $content->replace('/^[ ]{0,3}\>[ ]?/m', '');

                // TODO: Close blockquote when we're done.
                $this->stack->acceptBlockquote(new Blockquote());

                $this->next->parseBlock($content);
            },
            function (Text $part) {
                $this->next->parseBlock($part);
            }
        );
    }

}
