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
                (
                    (?:                             # We are either at the beginning of the match
                        ^|\n                        # or at a newline (for multi-line quotes)
                    )
                    (?:
                        [ ]{0,3}\>[ ]*              # either a blank line
                      |
                        [ ]{0,3}\>[ ]?[`~]{3,}.*    # or a code fence
                      |
                        [ ]{0,3}\>                  # or non-blank lines...
                        [ ]*[^\n\ ][^\n]*[ ]*
                        (                           # ...with lazy continuation
                            \n
                            [ ]{0,3}
                            [^>\-*=\ \n][^\n]*
                        )*
                    )
                )+
                $
            /mx',
            function (Text $content) use ($target) {
                // Remove block quote marker plus surrounding whitespace on each line
                $content->replace('/^[ ]{0,3}\>[ ]?/m', '');

                $blockquote = new Blockquote();
                $target->addChild($blockquote);

                $this->first->parseBlock($content, $blockquote);
            },
            function (Text $part) use ($target) {
                $this->next->parseBlock($part, $target);
            }
        );
    }

}
