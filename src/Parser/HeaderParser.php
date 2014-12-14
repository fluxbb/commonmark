<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Heading;
use FluxBB\Markdown\Node\Node;

class HeaderParser implements ParserInterface
{

    public function parseLine(Text $line, Node $target, callable $next)
    {
        // TODO: If a heading is found, there should be nothing else to parse.
        if (preg_match('{
            ^[ ]{0,3}       # Optional leading spaces
            (\#{1,6})       # $1 = string of #\'s
            (([ ].+?)??)    # $2 = Header text
            ([ ]\#*[ ]*)?   # optional closing #\'s (not counted)
            $
        }x', $line->getString(), $matches)) {
            $marks = $matches[1];
            $content = new Text($matches[2]);
            $level = strlen($marks);

            return $target->accept(new Heading($content->trim(), $level));
        }

        return $next($line, $target);
    }

}
