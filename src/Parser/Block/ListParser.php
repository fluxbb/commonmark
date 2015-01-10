<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Node\Block\ListBlock;
use FluxBB\CommonMark\Node\Block\ListItem;
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
        $this->parseBulletLists($content, $target);
    }

    protected function parseBulletLists(Text $content, Container $target)
    {
        $content->handle(
            '{
                ^
                ([ ]{0,3})              # $1 - initial indent
                ([\-+*])                # $2 - list marker
                (?|
                    ([ ]{1,4})          # $3 - list indent
                    [^ ].*
                  |
                    ()                  # ... which can also be empty
                )
                (
                    \n\n?
                    \1[ ]\3
                    .*
                )*
                (
                    (?:
                        \n
                        \1\2\3
                        [^ ].*
                      |                     # empty items
                        \n
                        \1\2
                      |                     # Lazy continuation lines
                        \n
                        [ ]*
                        [^>\-+*=\ \n][^\n]*
                    )
                    (
                        \n\n?
                        \1[ ]\3
                        .*
                    )*
                )*
                $
            }mx',
            function (Text $content, Text $i, Text $marker, Text $indent) use ($target) {
                $lines = explode("\n", $content->getString());
                $marker = $marker->getString();
                $indentLength = $i->getLength() + $indent->getLength() + 1;

                $list = new ListBlock('ul');

                // Go through all the lines to assemble the list items
                $curItem = substr(array_shift($lines), $indentLength) . "\n";
                foreach ($lines as $line) {
                    if (strpos($line, $marker) === 0) {
                        $this->addItemToList($curItem, $list);
                        $curItem = substr($line, $indentLength) . "\n";
                    } else {
                        $curItem .= $this->unindentLine($line, $indentLength) . "\n";
                    }
                }
                $this->addItemToList($curItem, $list);

                $target->addChild($list);
            },
            function (Text $part) use ($target) {
                $this->parseOrderedLists($part, $target);
            }
        );
    }

    protected function parseOrderedLists(Text $content, Container $target)
    {
        $content->handle(
            '{
                ^
                ([ ]{0,3})              # $1 - initial indent
                ([0-9]+)([.)])          # $2 - list marker; $3 - punctuation
                (?|
                    ([ ]{1,4})          # $4 - list indent
                    [^ ].*
                  |
                    ()                  # ... which can also be empty
                )
                (
                    \n\n?
                    \1[ ]{2}\4
                    .*
                )*
                (
                    (?:
                        \n
                        \1[0-9]+\3\4
                        [^ ].*
                      |                     # empty items
                        \n
                        \1\2
                      |                     # Lazy continuation lines
                        \n
                        [ ]*
                        [^>\-+*=\ \n][^\n]*
                    )
                    (
                        \n\n?
                        \1[ ]{2}\4
                        .*
                    )*
                )*
                $
            }mx',
            function (Text $content, Text $i, Text $start, Text $punctuation, Text $indent) use ($target) {
                $lines = explode("\n", $content->getString());
                $start = $start->getString();
                $indentLength = $i->getLength() + $indent->getLength() + 2;

                $list = new ListBlock('ol', $start);

                // Go through all the lines to assemble the list items
                $curItem = substr(array_shift($lines), $indentLength) . "\n";
                foreach ($lines as $line) {
                    if (preg_match('/^[0-9]+' . preg_quote($punctuation) . '/', $line)) {
                        $this->addItemToList($curItem, $list);
                        $curItem = substr($line, $indentLength) . "\n";
                    } else {
                        $curItem .= $this->unindentLine($line, $indentLength) . "\n";
                    }
                }
                $this->addItemToList($curItem, $list);

                $target->addChild($list);
            },
            function (Text $part) use ($target) {
                $this->next->parseBlock($part, $target);
            }
        );
    }

    protected function unindentLine($line, $indentLength)
    {
        return preg_replace('/^[ ]{0,' . $indentLength . '}/', '', $line);
    }

    protected function addItemToList($text, ListBlock $list)
    {
        $text = new Text(rtrim($text));
        $item = new ListItem();

        $list->addChild($item);

        $this->first->parseBlock($text, $item);

        if ($item->shouldBeTerse()) {
            $item->terse();
        }
    }

}
