<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\ListBlock;
use FluxBB\Markdown\Node\ListItem;

class ListParser extends AbstractParser
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
        $pattern = '/^[ ]{0,3}-[ ]+/';

        $block->handle(
            $pattern,
            function (Text $line) use ($pattern) {
                $line->replace($pattern, '');

                $list = new ListBlock(new ListItem($line));
                $this->stack->acceptListBlock($list);
            },
            function (Text $part) {
                $this->next->parseBlock($part);
            }
        );
    }

}
