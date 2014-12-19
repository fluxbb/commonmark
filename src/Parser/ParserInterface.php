<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;

interface ParserInterface
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
    public function parse(Text $content);

}
