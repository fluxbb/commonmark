<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Blockquote;

class BlockquoteParser extends AbstractParser
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
            '/^[ ]{0,3}\>([ ]?)/m',
            function (Text $whole, Text $content) {
                $this->stack->acceptBlockquote(new Blockquote());

                $this->next->parseBlock($content);
            },
            function (Text $part) {
                $this->next->parseBlock($part);
            }
        );
    }

}
