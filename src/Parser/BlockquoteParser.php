<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Blockquote;
use FluxBB\Markdown\Node\NodeAcceptorInterface;

class BlockquoteParser implements ParserInterface
{

    public function parseLine(Text $line, NodeAcceptorInterface $target, callable $next)
    {
        $text = $line->getString();
        if ($text[0] == '>') {
            $line = new Text(trim(substr($line, 1)));

            $quote = new Blockquote();
            $target = $target->accept($quote);
        }

        return $next($line, $target);
    }

}
