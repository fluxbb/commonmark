<?php

namespace FluxBB\Markdown\Parser\Block;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Blockquote;
use FluxBB\Markdown\Parser\AbstractParser;

class BlockquoteParser extends AbstractParser
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
    public function parse(Text $content)
    {
        $content->handle(
            '/^[ ]{0,3}\>[ ]?(.+)/m',
            function (Text $whole, Text $content) {
                $this->stack->acceptBlockquote(new Blockquote());

                $this->next->parse($content);
            },
            function (Text $part) {
                $this->next->parse($part);
            }
        );
    }

}
