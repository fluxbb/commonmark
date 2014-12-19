<?php

namespace FluxBB\Markdown\Parser\Block;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\HorizontalRule;
use FluxBB\Markdown\Parser\AbstractParser;

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
        $this->parseStars($block);
    }

    protected function parseStars(Text $block)
    {
        $this->parse(
            $block, '*',
            function (Text $part) {
                $this->parseDashes($part);
            }
        );
    }

    protected function parseDashes(Text $block)
    {
        $this->parse(
            $block, '-',
            function (Text $part) {
                $this->parseUnderscores($part);
            }
        );
    }

    protected function parseUnderscores(Text $block)
    {
        $this->parse(
            $block, '_',
            function (Text $part) {
                $this->next->parseBlock($part);
            }
        );
    }

    protected function parse(Text $block, $mark, callable $next)
    {
        $block->handle(
            $this->getPattern($mark),
            function () {
                $this->stack->acceptHorizontalRule(new HorizontalRule());
            },
            $next
        );
    }

    protected function getPattern($mark)
    {
        return '/^[ ]{0,3}(' . preg_quote($mark, '/') . '[ ]*){3,}[ \t]*$/m';
    }

}
