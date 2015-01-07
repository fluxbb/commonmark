<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\CodeBlock;
use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Parser\AbstractBlockParser;

class FencedCodeBlockParser extends AbstractBlockParser
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
            }msx',
            function (Text $whole, Text $whitespace, Text $fence, Text $fenceChar, Text $lang, Text $code) use ($target) {
                $leading = $whitespace->getLength();

                $language = new Text(explode(' ', $lang->trim())[0]);
                $language->decodeEntities();

                // Remove all leading whitespace from content lines
                if ($leading > 0) {
                    $code->replace("/^[ ]{0,$leading}/m", '');
                }

                $target->acceptCodeBlock(new CodeBlock($code, $language));
            },
            function (Text $part) use ($target) {
                $this->next->parseBlock($part, $target);
            }
        );
    }

}
