<?php

namespace FluxBB\Markdown\Parser\Inline;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Code;
use FluxBB\Markdown\Node\HardBreak;
use FluxBB\Markdown\Node\InlineNodeAcceptorInterface;
use FluxBB\Markdown\Parser\AbstractInlineParser;

class LineBreakParser extends AbstractInlineParser
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
        $content->handle(
            '/(\\\\\n)|( {2,}\n)/',
            function () use ($target) {
                $target->addInline(new HardBreak());
            },
            function (Text $part) use ($target) {
                $this->next->parseInline($part, $target);
            }
        );
    }

}