<?php

namespace FluxBB\CommonMark\Parser;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\InlineNodeAcceptorInterface;

interface InlineParserInterface
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
    public function parseInline(Text $content, InlineNodeAcceptorInterface $target);

}
