<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\CodeBlock;
use FluxBB\Markdown\Node\Node;

class CodeBlockParser implements ParserInterface
{

    public function parseLine(Text $line, Node $target, callable $next)
    {
        $pattern = '/^[ ]{4}/';

        if ($line->match($pattern)) {
            return $target->accept(new CodeBlock($line));
        }

        return $next($line, $target);
    }

}
