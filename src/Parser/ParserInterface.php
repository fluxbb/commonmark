<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Node;

interface ParserInterface
{

    /**
     * Parse the given line.
     *
     * Any newly created nodes should be pushed to the given target node. If parsing of the line was not complete or not
     * done at all, the given closure should be called to pass on control to the next parser in the chain.
     *
     * This method should return the node that was last to be created.
     *
     * @param Text $line
     * @param Node $target
     * @param callable $next
     * @return \FluxBB\Markdown\Node\Node
     */
    public function parseLine(Text $line, Node $target, callable $next);

}
