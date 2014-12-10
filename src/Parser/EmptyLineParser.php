<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Node;

class EmptyLineParser implements ParserInterface
{

    public function parseLine(Text $line, Node $target, callable $next)
    {
        if ($line->trim()->isEmpty()) {
            return $target;
        }

        return $next($line, $target);
    }

}
