<?php

namespace FluxBB\Markdown\Parser\Inline;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Image;
use FluxBB\Markdown\Node\InlineNodeAcceptorInterface;
use FluxBB\Markdown\Parser\AbstractInlineParser;

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
                (               # wrap whole match in $1
                  !\[
                    (.*?)       # alt text = $2
                  \]
                  \(            # literal paren
                    [ \t]*
                    <?(\S+?)>?  # src url = $3
                    [ \t]*
                    (           # $4
                      ([\'"])   # quote char = $5
                      (.*?)     # title = $6
                      \5        # matching quote
                      [ \t]*
                    )?          # title is optional
                  \)
                )
            }xs',
            function (Text $w, Text $whole, Text $alt, Text $url, Text $a = null, Text $q = null, Text $title = null) use ($target) {
                // TODO: Alt text, title
                $target->addInline(new Image($url->escapeHtml()));
            },
            function (Text $part) use ($target) {
                $this->next->parseInline($part, $target);
            }
        );
    }

}