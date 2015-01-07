<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Blockquote;
use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Parser\AbstractBlockParser;

class BlockquoteParser extends AbstractBlockParser
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
            '/
                ^
                [ ]{0,3}        # up to 3 leading spaces
                \>              # block quote marker
                [ ]?            # an optional space
                [^\n]*          # until end of line
                (?:                 # lazy continuation
                    \n
                    [^\-*=\ ].*
                )*
                $
            /mx',
            function (Text $content) use ($target) {
                // Remove block quote marker plus surrounding whitespace on each line
                $content->replace('/^[ ]{0,3}\>[ ]?/m', '');

                $blockquote = new Blockquote();
                $target->acceptBlockquote($blockquote);

                $this->first->parseBlock($content, $blockquote);
            },
            function (Text $part) use ($target) {
                $this->next->parseBlock($part, $target);
            }
        );
    }

}
