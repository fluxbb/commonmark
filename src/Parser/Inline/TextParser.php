<?php

namespace FluxBB\CommonMark\Parser\Inline;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\InlineNodeAcceptorInterface;
use FluxBB\CommonMark\Node\Inline\String;
use FluxBB\CommonMark\Parser\AbstractInlineParser;

class TextParser extends AbstractInlineParser
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
        $this->handleBackslashes($content);

        $content->decodeEntities();

        // Remove spaces at the end of each line
        $content->replace('/[ ]+(?=\n)/', '');

        $target->addInline(new String($content));
    }

    public function handleBackslashes(Text $content)
    {
        $content->replaceString([
            '\\\\', '\!', '\"', '\#', '\$', '\%', '\&', '\\\'', '\(', '\)', '\*', '\+', '\,', '\-', '\.', '\/', '\:',
            '\;', '\<', '\=', '\>', '\?', '\@', '\[', '\]', '\^', '\_', '\`', '\{', '\|', '\}', '\~',
        ], [
            '\\', '!', '"', '#', '$', '%', '&', '\'', '(', ')', '*', '+', ',', '-', '.', '/', ':',
            ';', '<', '=', '>', '?', '@', '[', ']', '^', '_', '`', '{', '|', '}', '~',
        ]);
    }

}