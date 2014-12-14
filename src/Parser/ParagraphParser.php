<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Node;
use FluxBB\Markdown\Node\Paragraph;

class ParagraphParser implements ParserInterface
{

    public function parseLine(Text $line, Node $target, callable $next)
    {
        return $target->acceptParagraph(new Paragraph($line));
    }

}
