<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Node\ListBlock;
use FluxBB\CommonMark\Node\ListItem;
use FluxBB\CommonMark\Parser\AbstractBlockParser;

class ListParser extends AbstractBlockParser
{

    /**
     * Parse the given content.
     *
     * Any newly created nodes should be pushed to the stack. Any remaining content should be passed to the next parser
     * in the chain.
     *
     * @param Text $content
     * @param Container $target
     * @return void
     */
    public function parseBlock(Text $content, Container $target)
    {
        $content->handle(
            '{
                ^
                ([\-+*])                  # $1 - list marker
                ([ ]{1,4})                # $2 - initial indent
                [^ ].*
                (
                    \n\n?
                    [ ]\2
                    .*
                )*
                (
                    \n
                    \1\2
                    [^ ].*
                    (
                        \n\n?
                        [ ]\2
                        .*
                    )*
                )*
                $
            }mx',
            function (Text $content, Text $marker, Text $indent) use ($target) {
                $lines = explode("\n", $content->getString());
                $marker = $marker->getString();
                $indentLength = $indent->getLength() + 1;

                $list = new ListBlock('ul');

                // Go through all the lines to assemble the list items
                $curItem = substr(array_shift($lines), $indentLength) . "\n";
                foreach ($lines as $line) {
                    if (strpos($line, $marker) === 0) {
                        $list->addChild(new ListItem(new Text(rtrim($curItem))));
                        $curItem = '';
                    }

                    $curItem .= substr($line, $indentLength) . "\n";
                }
                $list->addChild(new ListItem(new Text(rtrim($curItem))));

                $target->acceptListBlock($list);
            },
            function (Text $part) use ($target) {
                $this->next->parseBlock($part, $target);
            }
        );
    }

}
