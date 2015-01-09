<?php

namespace FluxBB\CommonMark\Parser\Inline;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Inline\Code;
use FluxBB\CommonMark\Node\InlineNodeAcceptorInterface;
use FluxBB\CommonMark\Parser\AbstractInlineParser;

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
                (?<![`\\\\])
                (`+)        # $1 = Opening run of `
                (?!`)
                (.+?)       # $2 = The code block
                (?<!`)
                \1          # Matching closer
                (?!`)
            }sx',
            function (Text $whole, Text $b, Text $code) use ($target) {
                // Replace multiple whitespace characters in a row with a single space
                $code->trim()->replaceString("\n", ' ')->replace('/[\s]{2,}/', ' ');

                $target->addInline(new Code($code->escapeHtml()));
            },
            function (Text $part) use ($target) {
                $this->next->parseInline($part, $target);
            }
        );
    }

}