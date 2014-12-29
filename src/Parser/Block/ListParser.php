<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\ListBlock;
use FluxBB\CommonMark\Node\ListItem;
use FluxBB\CommonMark\Parser\AbstractBlockParser;

class ListParser extends AbstractBlockParser
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
        $pattern = '/^[ ]{0,3}-[ ]+/';

        $content->handle(
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
