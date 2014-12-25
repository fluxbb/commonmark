<?php

namespace FluxBB\CommonMark\Parser\Inline;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\InlineNodeAcceptorInterface;
use FluxBB\CommonMark\Node\StrongEmphasis;
use FluxBB\CommonMark\Parser\AbstractInlineParser;

class StrongEmphasisParser extends AbstractInlineParser
{

    /**
     * Parse the given content.
     *
     * Any newly created nodes should be appended to the given target. Any remaining content should be passed to the
     * next parser in the chain.
     *
     * @param Text $content
     * @param InlineNodeAcceptorInterface $target
     * @return void
     */
    public function parseInline(Text $content, InlineNodeAcceptorInterface $target)
    {
        if ($content->contains('**') || $content->contains('__')) {
            $this->parseStars($content, $target);
        } else {
            $this->next->parseInline($content, $target);
        }
    }

    protected function parseStars(Text $content, InlineNodeAcceptorInterface $target)
    {
        $content->handle(
            '{ (\*\*) (?![\s*]) (.+) (?<![\s*]) \1 }sx',
            function (Text $w, Text $a, Text $inner) use ($target) {
                $target->addInline(new StrongEmphasis($inner->getString()));
            },
            function (Text $part) use ($target) {
                $this->parseUnderscores($part, $target);
            }
        );
    }

    protected function parseUnderscores(Text $content, InlineNodeAcceptorInterface $target)
    {
        $content->handle(
            '{ (?<![A-Za-z0-9]) (__) (?![\s_]) (.+) (?<![\s_]) \1 (?![A-Za-z0-9]) }sx',
            function (Text $w, Text $a, Text $inner) use ($target) {
                $target->addInline(new StrongEmphasis($inner->getString()));
            },
            function (Text $part) use ($target) {
                $this->next->parseInline($part, $target);
            }
        );
    }

}