<?php

namespace FluxBB\CommonMark\Parser\Inline;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Image;
use FluxBB\CommonMark\Node\InlineNodeAcceptorInterface;
use FluxBB\CommonMark\Parser\AbstractInlineParser;

class ImageParser extends AbstractInlineParser
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
        if ($content->contains('![')) {
            $this->parseInlineImage($content, $target);
        } else {
            $this->next->parseInline($content, $target);
        }
    }

    protected function parseInlineImage(Text $content, InlineNodeAcceptorInterface $target)
    {
        $content->handle(
            '{
                (?<!\\\\)!
                \[
                (.*?)       # alt text = $1
                \]
                \(            # literal paren
                [ \t]*
                <?(\S*?)>?  # src url = $2
                [ \t]*
                (           # $3
                  ([\'"])   # quote char = $4
                  (.*?)     # title = $5
                  \4        # matching quote
                  [ \t]*
                )?          # title is optional
                \)
            }xs',
            function (Text $w, Text $alt, Text $url, Text $a = null, Text $q = null, Text $title = null) use ($target) {
                $image = new Image($url, $alt, $title);
                $target->addInline($image);
            },
            function (Text $part) use ($target) {
                $this->parseReferenceImage($part, $target);
            }
        );
    }

    protected function parseReferenceImage(Text $content, InlineNodeAcceptorInterface $target)
    {
        $references = implode('|', array_map(function ($reference) {
            return str_replace(' ', '[ ]', preg_quote($reference));
        }, $this->context->getReferences()));

        $content->handle(
            '{
                (?<!\\\\)!
                (?|
                    ()
                    \[
                        (' . $references . ')
                    \]
                    [\n\ ]*
                    \[\]
                  |
                    (?:
                        \[
                            (.*?)       # alt text = $1
                        \]
                        [ \t\n]*
                    )?
                    \[
                        (' . $references .')
                    \]
                )
            }iux',
            function (Text $whole, Text $alt, Text $label) use ($target) {
                $reference = $label->copy()->lower();
                $url = $this->context->getReferenceUrl($reference);
                $title = $this->context->getReferenceTitle($reference);

                if ($alt->isEmpty()) {
                    $alt = $label;
                }

                $image = new Image($url, $alt, $title);
                $target->addInline($image);
            },
            function (Text $part) use ($target) {
                $this->next->parseInline($part, $target);
            }
        );
    }

}