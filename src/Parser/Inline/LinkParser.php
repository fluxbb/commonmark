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
                (?<!\\\\)
                \[
                    (' . $this->getNestedBrackets() . ')    # link text = $1
                \]
                \(                        # literal paren
                    [ \t\n]*
                    (?|                   # href = $2
                        ([^\s<>]*)
                        |
                        <([^<>\\n]*)>
                    )
                    (?:
                        [ \t\n]+
                        (?|
                            ([\'"])       # quote char = $3
                            (.*)          # title = $4
                            \3            # matching quote
                            |
                            \(            # opening paren
                            ()            # empty quote = $3
                            (.*)          # title = $4
                            \)            # closing paren
                        )
                        [ \t\n]*
                    )?                    # title is optional
                \)
            }x',
            function (Text $whole, Text $linkText, Text $url, Text $q = null, Text $title = null) use ($target) {
                $url->decodeEntities();

                if ($title) {
                    $title->decodeEntities();
                }

                // Replace special characters in the URL
                $url->encodeUrl();

                $target->addInline(new Link($url, $linkText, $title));
            },
            function (Text $part) use ($target) {
                $this->parseReferenceLink($part, $target);
            }
        );
    }

    protected function parseReferenceLink(Text $content, InlineNodeAcceptorInterface $target)
    {
        $references = implode('|', array_map(function ($reference) {
            return str_replace(' ', '[ ]', preg_quote($reference));
        }, $this->context->getReferences()));

        $content->handle(
            '/
                (?<!\\\\)
                (?:
                    \[
                        (' . $this->getNestedBrackets() . ')    # link text = $1
                    \]
                    [ \t\n]*
                )?
                \[
                    (' . $references . ')                       # label = $2
                \]
            /ix',
            function (Text $whole, Text $linkText, Text $label) use ($target) {
                $url = $this->context->getReferenceUrl($label->copy()->lower());
                $title = $this->context->getReferenceTitle($label);

                if ($linkText->isEmpty()) {
                    $linkText = $label;
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