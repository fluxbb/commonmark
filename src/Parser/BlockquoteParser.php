<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Node\Blockquote;
use FluxBB\Markdown\Node\NodeAcceptorInterface;

class BlockquoteParser implements ParserInterface
{

    public function parseLine($line, NodeAcceptorInterface $target, callable $next)
    {
        if ($line[0] == '>') {
            $line = trim(substr($line, 1));

            $quote = new Blockquote();
            $target = $target->accept($quote);
        }

        return $next($line, $target);
    }

}
