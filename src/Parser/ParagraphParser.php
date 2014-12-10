<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Node\NodeAcceptorInterface;
use FluxBB\Markdown\Node\Paragraph;

class ParagraphParser implements ParserInterface
{

    public function parseLine($line, NodeAcceptorInterface $target, callable $next)
    {
        $paragraph = new Paragraph($line);

        return $target->accept($paragraph);
    }

}
