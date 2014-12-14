<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Blockquote;
use FluxBB\Markdown\Node\Node;

class BlockquoteParser implements ParserInterface
{

    public function parseLine(Text $line, Node $target, callable $next)
    {
        $pattern = '/^[ ]{0,3}\>[ ]?/';

        if ($line->match($pattern)) {
            $line->replace($pattern, '');

            $target = $target->acceptBlockquote(new Blockquote());
        }

        return $next($line, $target);
    }

}
