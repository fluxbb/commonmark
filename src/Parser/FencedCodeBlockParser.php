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
            (?<=\n|\A)
            (?:
                ([ ]{0,3})                  #1 Initial indentation
                (                           #2 Fence
                    ([`~])                  #3 The fence character (` or ~)
                    \3{2,}                  #  At least two remaining fence characters
                )
                ([^`]*?)?                   #4 Code language [optional]
                \n
                (.*?)                       #5 Code block
                (?:([ ]{0,3}\2\3*[ ]*)|\z)  #  Closing fence or end of document
            )
        }sx', function (Text $whole, Text $whitespace, Text $fence, Text $fenceChar, Text $lang, Text $code) {
            $leading = $whitespace->getLength();

            // Remove all leading whitespace from content lines
            if ($leading > 0) {
                $code->replace("/^[ ]{0,$leading}/m", '');
            }

            $language = explode(' ', $lang->trim())[0];

            /*$this->markdown->emit('detab', array($code));
            $code->replace('/\A\n+/', '');
            $code->replace('/\s+\z/', '');*/

            $this->stack->acceptCodeBlock(new CodeBlock($code, $language));
        }, function (Text $part) {
            $this->next->parseBlock($part);
        });
    }

}
