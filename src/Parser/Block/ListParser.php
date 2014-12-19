<?php

namespace FluxBB\Markdown\Parser\Block;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\ListBlock;
use FluxBB\Markdown\Node\ListItem;
use FluxBB\Markdown\Parser\AbstractParser;

class ListParser extends AbstractParser
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
        $pattern = '/^[ ]{0,3}-[ ]+/';

        $content->handle(
            $pattern,
            function (Text $line) use ($pattern) {
                $line->replace($pattern, '');

                $list = new ListBlock(new ListItem($line));
                $this->stack->acceptListBlock($list);
            },
            function (Text $part) {
                $this->next->parse($part);
            }
        );
    }

}
