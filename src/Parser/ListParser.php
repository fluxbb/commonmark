<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\ListBlock;
use FluxBB\Markdown\Node\ListItem;
use FluxBB\Markdown\Node\Node;

class ListParser implements ParserInterface
{

    public function parseLine(Text $line, Node $target, callable $next)
    {
        $pattern = '/^[ ]{0,3}-[ ]+/';

        if ($line->match($pattern)) {
            $line->replace($pattern, '');

            $list = new ListBlock(new ListItem($line));
            $target = $target->acceptListBlock($list);
        }

        return $next($line, $target);
    }

}
