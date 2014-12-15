<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\CodeBlock;

class CodeBlockParser extends AbstractParser
{

    public function parseBlock(Text $block)
    {
        $block->handle(
            '{
                (?:\n\n|\A)
                (                      # $1 = the code block -- one or more lines, starting with a space/tab
                  (?:
                    (?:[ ]{4} | \t)    # Lines must start with a tab or a tab-width of spaces
                    .*\n+
                  )+
                )
                (?:(?=^[ ]{0,4}\S)|\Z) # Lookahead for non-space at line-start, or end of doc
            }mx',
            function (Text $whole, Text $code) {
                // TODO: Prepare contents
                $this->stack->acceptCodeBlock(new CodeBlock($code));
            },
            function(Text $part) {
                $this->next->parseBlock($part);
            }
        );
    }

}
