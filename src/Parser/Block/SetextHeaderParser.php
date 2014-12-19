<?php

namespace FluxBB\Markdown\Parser\Block;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Heading;
use FluxBB\Markdown\Parser\AbstractParser;

class SetextHeaderParser extends AbstractParser
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
            '{^(.+)[ \t]*\n[ \t]{0,3}(=+|-+)[ \t]*\n+}m',
            function (Text $whole, Text $content, Text $mark) {
                $level = (substr($mark, 0, 1) == '=') ? 1 : 2;

                // TODO: Parse content as inline.

                $this->stack->acceptHeading(new Heading($content->trim(), $level));
            },
            function (Text $part) {
                $this->next->parse($part);
            }
        );
    }

}
