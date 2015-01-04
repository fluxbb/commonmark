<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\HorizontalRule;
use FluxBB\CommonMark\Parser\AbstractBlockParser;

class HorizontalRuleParser extends AbstractBlockParser
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
        $this->parseStars($content);
    }

    protected function parseStars(Text $content)
    {
        $this->handle(
            $content, '*',
            function (Text $part) {
                $this->parseDashes($part);
            }
        );
    }

    protected function parseDashes(Text $content)
    {
        $this->handle(
            $content, '-',
            function (Text $part) {
                $this->parseUnderscores($part);
            }
        );
    }

    protected function parseUnderscores(Text $content)
    {
        $this->handle(
            $content, '_',
            function (Text $part) {
                $this->next->parseBlock($part);
            }
        );
    }

    protected function handle(Text $content, $mark, callable $next)
    {
        $content->handle(
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
