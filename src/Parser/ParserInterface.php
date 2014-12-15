<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;

interface ParserInterface
{

    /**
     * Parse the given block content.
     *
     * Any newly created nodes should be pushed to the stack. Any remaining content should be passed to the next parser
     * in the chain.
     *
     * @param Text $block
     * @return void
     */
    public function parseBlock(Text $block);

}
