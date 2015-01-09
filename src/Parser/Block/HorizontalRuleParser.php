<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Container;
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
     * @param Container $target
     * @return void
     */
    public function parseBlock(Text $content, Container $target)
    {
        $this->parseStars($content, $target);
    }

    protected function parseStars(Text $content, Container $target)
    {
        $this->handle(
            $content, '*', $target,
            function (Text $part) use ($target) {
                $this->parseDashes($part, $target);
            }
        );
    }

    protected function parseDashes(Text $content, Container $target)
    {
        $this->handle(
            $content, '-', $target,
            function (Text $part) use ($target) {
                $this->parseUnderscores($part, $target);
            }
        );
    }

    protected function parseUnderscores(Text $content, Container $target)
    {
        $this->handle(
            $content, '_', $target,
            function (Text $part) use ($target) {
                $this->next->parseBlock($part, $target);
            }
        );
    }

    protected function handle(Text $content, $mark, Container $target, callable $next)
    {
        $content->handle(
            $this->getPattern($mark),
            function () use ($target) {
                $target->addChild(new HorizontalRule());
            },
            $next
        );
    }

    protected function getPattern($mark)
    {
        return '/^[ ]{0,3}(' . preg_quote($mark, '/') . '[ ]*){3,}[ \t]*$/m';
    }

}
