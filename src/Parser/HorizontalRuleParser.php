<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\HorizontalRule;

class HorizontalRuleParser extends AbstractParser
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
        $marks = ['*', '-', '_'];

        foreach ($marks as $mark) {
            $block->handle(
                '/^[ ]{0,3}(' . preg_quote($mark, '/') . '[ ]*){3,}[ \t]*$/',
                function () {
                    $this->stack->acceptHorizontalRule(new HorizontalRule());
                },
                function (Text $part) {
                    $this->next->parseBlock($part);
                }
            );
        }
    }

}
