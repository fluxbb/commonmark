<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\HTMLBlock;
use FluxBB\CommonMark\Parser\AbstractBlockParser;

class HTMLBlockParser extends AbstractBlockParser
{

    /**
     * Parse the given content.
     *
     * Any newly created nodes should be pushed to the stack. Any remaining content should be passed to the next parser
     * in the chain.
     *
     * @param Text $content
     * @return void
     */
    public function parseBlock(Text $content)
    {
        $tags = implode('|', $this->getValidTags());

        $content->handle('{
            ^                    # starts at the beginning or with a newline
            [ ]{0,3}             # up to 3 leading spaces allowed
            \<(?:'.$tags.')\>    # start with a HTML tag
            .*?                  # match everything until...
            (\\n[ ]*\\n|\Z)      # we encounter an empty line or the end
        }imsx', function (Text $content) {
            $block = new HTMLBlock($content);

            $this->stack->acceptHTMLBLock($block);
        }, function (Text $part) {
            $this->next->parseBlock($part);
        });
    }

    protected function getValidTags()
    {
        return [
            'article', 'header', 'aside', 'hgroup', 'blockquote', 'hr', 'iframe', 'body', 'li', 'map', 'button',
            'object', 'canvas', 'ol', 'caption', 'output', 'col', 'p', 'colgroup', 'pre', 'dd', 'progress', 'div',
            'section', 'dl', 'table', 'td', 'dt', 'tbody', 'embed', 'textarea', 'fieldset', 'tfoot', 'figcaption',
            'th', 'figure', 'thead', 'footer', 'tr', 'form', 'ul', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'video',
            'script', 'style',
        ];
    }

}
