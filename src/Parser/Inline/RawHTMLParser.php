<?php

namespace FluxBB\CommonMark\Parser\Inline;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\InlineNodeAcceptorInterface;
use FluxBB\CommonMark\Node\RawHTML;
use FluxBB\CommonMark\Parser\AbstractInlineParser;

class RawHTMLParser extends AbstractInlineParser
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
            '{
                (?<!\\\\)
                (
                    \<[A-Z][A-Z0-9]*            # an opening HTML tag with
                        (?:                         # multiple attributes...
                            \s+
                            [A-Z_:][A-Z0-9_:.\-]*
                            (?:
                                \s*=\s*
                                (?:
                                    [^ "\'=<>`]+|   # unquoted attribute values
                                    \'[^\']*\'|     # single-quoted attribute values
                                    "[^"]*"         # double-quoted attribute values
                                )
                            )?
                        )*
                        \s*/?\>|                # ...or
                    \</[A-Z][A-Z0-9]*\s*\>|     # a closing HTML tag, or
                    \<!--.*?--\>|               # a HTML comment, or
                    \<\?.*?\?\>|                # a processing instruction, or
                    \<![A-Z]+\s+[^>]+\>|        # an element type declaration, or
                    \<!\[CDATA\[.*?\]\]\>       # a CDATA section
                )
            }isx',
            function (Text $content) use ($target) {
                $target->addInline(new RawHTML($content));
            },
            function (Text $part) use ($target) {
                $this->next->parseInline($part, $target);
            }
        );
    }

}