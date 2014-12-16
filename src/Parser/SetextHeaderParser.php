<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Heading;

class SetextHeaderParser extends AbstractParser
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
            '{^(.+)[ \t]*\n[ \t]{0,3}(=+|-+)[ \t]*\n+}m',
            function (Text $whole, Text $content, Text $mark) {
                $level = (substr($mark, 0, 1) == '=') ? 1 : 2;

                // TODO: Parse content as inline.

                $this->stack->acceptHeading(new Heading($content->trim(), $level));
            },
            function (Text $part) {
                $this->next->parseBlock($part);
            }
        );
    }

}
