<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Heading;
use FluxBB\CommonMark\Parser\AbstractParser;

class AtxHeaderParser extends AbstractParser
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
                $this->next->parse($part);
            }
        );
    }

}
