<?php

namespace FluxBB\Markdown\Parser\Block;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\CodeBlock;
use FluxBB\Markdown\Parser\AbstractParser;

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
            ^(?:
                ([ ]{0,3})                  #1 Initial indentation
                (                           #2 Fence
                    ([`~])                  #3 The fence character (` or ~)
                    \3{2,}                  #  At least two remaining fence characters
                )
                ([^`\n]*?)?                 #4 Code language [optional]
                \n(.*?)                     #5 Code block
                (?:(?<=\n)([ ]{0,3}\2\3*[ ]*)|\z)  #  Closing fence or end of document
            )$
        }msx', function (Text $whole, Text $whitespace, Text $fence, Text $fenceChar, Text $lang, Text $code) {
            $leading = $whitespace->getLength();

            $language = explode(' ', $lang->trim())[0];

            // Remove all leading whitespace from content lines
            if ($leading > 0) {
                $code->replace("/^[ ]{0,$leading}/m", '');
            }

            $this->stack->acceptCodeBlock(new CodeBlock($code, $language));
        }, function (Text $part) {
            $this->next->parseBlock($part);
        });
    }

}
