<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Blockquote;
use FluxBB\CommonMark\Parser\AbstractParser;

class BlockquoteParser extends AbstractParser
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
            '/
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

                $this->stack->acceptBlockquote(new Blockquote());

                $this->next->parse($content);
            },
            function (Text $part) {
                $this->next->parse($part);
            }
        );
    }

}
