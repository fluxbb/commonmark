<?php

namespace FluxBB\CommonMark\Parser\Inline;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\InlineNodeAcceptorInterface;
use FluxBB\CommonMark\Node\Link;
use FluxBB\CommonMark\Parser\AbstractInlineParser;

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
                \[
                    (' . $this->getNestedBrackets() . ')    # link text = $1
                \]
                \(                      # literal paren
                    [ \t]*
                    (                   # href = $2
                        (?:[\S]*)
                        |
                        (?:<[^<>\\n]*>)
                    )
                    [ \t]*
                    (                   # $3
                        ([\'"])         # quote char = $4
                        (.*?)           # Title = $5
                        \4              # matching quote
                        [ \t]*
                    )?                  # title is optional
                \)
            }xs',
            function (Text $whole, Text $linkText, Text $url, Text $a = null, Text $q = null, Text $title = null) use ($target) {
                if ($url->startsWith('<') && $url->endsWith('>')) {
                    $url->ltrim('<')->rtrim('>')->replaceString(' ', '%20');
                }

                $target->addInline(new Link($url, $linkText, $title));
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