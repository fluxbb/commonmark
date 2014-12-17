<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\CodeBlock;

class FencedCodeBlockParser extends AbstractParser
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
        $block->handle('{
            (?:\n\n|\A)
            (?:
                (                     #1 fence
                    ([`~])            #2 the fence character (` or ~)
                    \2{2,}            # at least two remaining fence characters
                )
                [ ]*
                ([a-zA-Z0-9]*?)?      #3 code language [optional]
                \n+
                (.*?)\n               #4 code block
                \1\2*                 # closing fence - at least as long as the opening one
            )
        }smx', function (Text $whole, Text $fence, Text $fenceChar, Text $lang, Text $code) {
            $code->escapeHtml(ENT_NOQUOTES);

            /*$this->markdown->emit('detab', array($code));
            $code->replace('/\A\n+/', '');
            $code->replace('/\s+\z/', '');*/

            $this->stack->acceptCodeBlock(new CodeBlock($code));
        }, function (Text $part) {
            $this->next->parseBlock($part);
        });
    }

}
