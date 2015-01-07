<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Node\Heading;
use FluxBB\CommonMark\Parser\AbstractBlockParser;

class SetextHeaderParser extends AbstractBlockParser
{

    /**
     * Parse the given content.
     *
     * Any newly created nodes should be pushed to the stack. Any remaining content should be passed to the next parser
     * in the chain.
     *
     * @param Text $content
     * @param Container $target
     * @return void
     */
    public function parseBlock(Text $content, Container $target)
    {
        $content->handle(
            '{
                ^
                [ ]{0,3}
                ([^>\-*=\ ].*)
                [ ]*
                \n
                [ ]{0,3}
                (=+|-+)
                [ ]*
                \n*
                $
            }mx',
            function (Text $whole, Text $content, Text $mark) use ($target) {
                $level = (substr($mark, 0, 1) == '=') ? 1 : 2;

                $target->acceptHeading(new Heading($content->trim(), $level));
            },
            function (Text $part) use ($target) {
                $this->next->parseBlock($part, $target);
            }
        );
    }

}
