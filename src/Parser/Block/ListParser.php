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
        $this->parseBulletLists($content, $target);
    }

    protected function parseBulletLists(Text $content, Container $target)
    {
        $content->handle(
            '{
                ^
                ([ ]{0,3})              # $1 - initial indent
                ([\-+*])                # $2 - list marker
                ([ ]{1,4})              # $3 - list indent
                [^ ].*
                (
                    \n\n?
                    \1[ ]\3
                    .*
                )*
                (
                    \n
                    \1\2\3
                    [^ ].*
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
                $indentLength = $indent->getLength() + 1;

                $list = new ListBlock('ul');

                // Go through all the lines to assemble the list items
                $curItem = substr(array_shift($lines), $indentLength) . "\n";
                foreach ($lines as $line) {
                    if (strpos($line, $marker) === 0) {
                        $this->addItemToList($curItem, $list);
                        $curItem = '';
                    }

                    $curItem .= substr($line, $indentLength) . "\n";
                }
                $this->addItemToList($curItem, $list);

                $target->acceptListBlock($list);
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
                ([ ]{1,4})              # $4 - initial indent
                [^ ].*
                (
                    \n\n?
                    \1[ ]{2}\4
                    .*
                )*
                (
                    \n
                    \1[0-9]+\3\4
                    [^ ].*
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
                    if (preg_match('/^[0-9]+' . $punctuation . '/', $line)) {
                        $this->addItemToList($curItem, $list);
                        $curItem = '';
                    }

                    $curItem .= substr($line, $indentLength) . "\n";
                }
                $this->addItemToList($curItem, $list);

                $target->acceptListBlock($list);
            },
            function (Text $part) use ($target) {
                $this->next->parseBlock($part, $target);
            }
        );
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
