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
                <?(\S+?)>?  # src url = $2
                [ \t]*
                (           # $3
                  ([\'"])   # quote char = $4
                  (.*?)     # title = $5
                  \5        # matching quote
                  [ \t]*
                )?          # title is optional
                \)
            }xs',
            function (Text $w, Text $alt, Text $url, Text $a = null, Text $q = null, Text $title = null) use ($target) {
                $target->addInline(new Image($url, $alt, $title));
            },
            function (Text $part) use ($target) {
                $this->next->parseInline($part, $target);
            }
        );
    }

}