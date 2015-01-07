<?php

namespace FluxBB\CommonMark\Parser;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Container;

interface BlockParserInterface
{

    /**
     * Parse the given content.
     *
     * Any newly created nodes should be pushed to the stack. Any remaining content should be passed to the next parser
     * in the chain.
     *
     * @param Text $content
     * @param Container $target
     * @return void
     */
    public function parseBlock(Text $content, Container $target);

}
