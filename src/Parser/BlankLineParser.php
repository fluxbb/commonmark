<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\BlankLine;
use FluxBB\Markdown\Node\Node;

class BlankLineParser implements ParserInterface
{

    public function parseLine(Text $line, Node $target, callable $next)
    {
        if ($line->copy()->trim()->isEmpty()) {
            return $target->acceptBlankLine(new BlankLine($line));
        }

        return $next($line, $target);
    }

}
