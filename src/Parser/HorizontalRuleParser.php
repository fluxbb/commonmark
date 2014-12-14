<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\HorizontalRule;
use FluxBB\Markdown\Node\Node;

class HorizontalRuleParser implements ParserInterface
{

    public function parseLine(Text $line, Node $target, callable $next)
    {
        $text = $line->getString();

        $marks = ['*', '-', '_'];

        foreach ($marks as $mark) {
            if (preg_match(
                '/^[ ]{0,3}(' . preg_quote($mark, '/') . '[ ]*){3,}[ \t]*$/',
                $text
            )) {
                return $target->acceptHorizontalRule(new HorizontalRule());
            }
        }

        return $next($line, $target);
    }

}
