<?php

namespace FluxBB\Markdown\Parser\Inline;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Code;
use FluxBB\Markdown\Node\InlineNodeAcceptorInterface;
use FluxBB\Markdown\Parser\AbstractInlineParser;

class CodeSpanParser extends AbstractInlineParser
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
        if ($content->contains('`')) {
            $this->parseCodeSpan($content, $target);
        } else {
            $this->next->parseInline($content, $target);
        }
    }

    protected function parseCodeSpan(Text $content, InlineNodeAcceptorInterface $target)
    {
        $content->handle(
            '{
                (`+)        # $1 = Opening run of `
                (.+?)       # $2 = The code block
                (?<!`)
                \1          # Matching closer
                (?!`)
            }x',
            function (Text $whole, Text $b, Text $code) use ($target) {
                $target->addInline(new Code($code->trim()->escapeHtml()));
            },
            function (Text $part) use ($target) {
                $this->next->parseInline($part, $target);
            }
        );
    }

}