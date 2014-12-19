<?php

namespace FluxBB\Markdown\Parser\Inline;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\InlineNodeAcceptorInterface;
use FluxBB\Markdown\Node\Link;
use FluxBB\Markdown\Parser\AbstractInlineParser;

class LinkParser extends AbstractInlineParser
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
        if ($content->contains('[')) {
            $this->parseInlineLink($content, $target);
        } else {
            $this->next->parseInline($content, $target);
        }
    }

    protected function parseInlineLink(Text $content, InlineNodeAcceptorInterface $target)
    {
        $content->handle(
            '{
                #(               # wrap whole match in $1
                  \[
                    (' . $this->getNestedBrackets() . ')    # link text = $2
                  \]
                  \(            # literal paren
                    [ \t]*
                    <?(.*?)>?   # href = $3
                    [ \t]*
                    (           # $4
                      ([\'"])   # quote char = $5
                      (.*?)     # Title = $6
                      \4        # matching quote
                    )?          # title is optional
                  \)
                #)
            }xs',
            function (Text $whole, Text $linkText, Text $url, Text $a = null, Text $q = null, Text $title = null) use ($target) {
                // TODO: title
                $target->addInline(new Link($url->escapeHtml(), $linkText));
            },
            function (Text $part) use ($target) {
                $this->next->parseInline($part, $target);
            }
        );
    }

    /**
     * @return string
     */
    protected function getNestedBrackets()
    {
        return str_repeat('(?>[^\[\]]+|\[', 7) . str_repeat('\])*', 7);
    }

}