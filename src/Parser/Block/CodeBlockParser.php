<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\CodeBlock;
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
     * @return void
     */
    public function parseBlock(Text $content)
    {
        $content->handle(
            '{
                (?:\n\n|\A)
                (                      # $1 = the code block -- one or more lines, starting with at least four spaces
                  (?:
                    (?:[ ]{4})         # Lines must start with four spaces
                    .*\n+
                  )+
                )
                (?:(?=^[ ]{0,4}\S)|\Z) # Lookahead for non-space at line-start, or end of doc
            }mx',
            function (Text $whole, Text $code) {
                // TODO: Prepare contents

                // Remove indent
                $code->replace('/^(\t|[ ]{1,4})/m', '');

                $this->stack->acceptCodeBlock(new CodeBlock($code));
            },
            function(Text $part) {
                $this->next->parseBlock($part);
            }
        );
    }

}
