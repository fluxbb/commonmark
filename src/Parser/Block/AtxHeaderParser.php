<?php

namespace FluxBB\Markdown\Parser\Block;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Heading;
use FluxBB\Markdown\Parser\AbstractParser;

class AtxHeaderParser extends AbstractParser
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
            '{
                ^[ ]{0,3}       # Optional leading spaces
                (\#{1,6})       # $1 = string of #\'s
                (([ ].+?)??)    # $2 = Header text
                ([ ]\#*[ ]*)?   # optional closing #\'s (not counted)
                $
            }mx',
            function (Text $whole, Text $marks, Text $content) {
                $level = $marks->getLength();

                $this->stack->acceptHeading(new Heading($content->trim(), $level));
            },
            function (Text $part) {
                $this->next->parseBlock($part);
            }
        );
    }

}
