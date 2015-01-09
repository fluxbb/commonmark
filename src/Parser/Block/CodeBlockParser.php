<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\CodeBlock;
use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Parser\AbstractBlockParser;

class CodeBlockParser extends AbstractBlockParser
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
                (?:                 # Ensure blank line before (or beginning of subject)
                    \A\s*\n?|
                    \n\s*\n
                )
                [ ]{4}              # four leading spaces
                .+
                (?:                 # optionally more spaces
                    (?:             # blank lines in between are okay
                        \n[ ]*
                    )*
                    \n
                    [ ]{4}
                    .+
                )*
                $
            }mx',
            function (Text $code) use ($target) {
                // Remove leading blank lines
                $code->replace('/^(\s*\n)*/', '');

                // Remove indent
                $code->replace('/^[ ]{1,4}/m', '');
                $code->append("\n");

                $target->addChild(new CodeBlock($code));
            },
            function(Text $part) use ($target) {
                $this->next->parseBlock($part, $target);
            }
        );
    }

}
