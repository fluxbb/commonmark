<?php

namespace FluxBB\Markdown\Parser\Block;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Node;
use FluxBB\Markdown\Parser\AbstractParser;

class LinkReferenceParser extends AbstractParser
{

    /**
     * @var Text[]
     */
    protected $links = [];

    /**
     * @var Text[]
     */
    protected $titles = [];


    public function parseLine(Text $line, Node $target, callable $next)
    {
        return $next($line, $target);
    }

    public function parse(Text $text, Node $target, callable $next)
    {
        $text->handle('{
            ^[ ]{0,4}\[(.+)\]:   # id = $1
              [ \t]*
              \n?               # maybe *one* newline
              [ \t]*
            <?(\S+?)>?          # url = $2
              [ \t]*
              \n?               # maybe one newline
              [ \t]*
            (?:
                (?<=\s)         # lookbehind for whitespace
                ["\'(]
                (.+?)           # title = $3
                ["\')]
                [ \t]*
            )?  # title is optional
            (?:\n+|\Z)
        }xm', function (Text $whole, Text $id, Text $url, Text $title = null) use ($target) {
            $id = $id->lower()->getString();

            $this->links[$id] = $url;

            if ($title) {
                $this->titles[$id] = $title;
            }
        }, function (Text $part) {
            // Parse block
        });
    }

    /**
     * Parse the given block content.
     *
     * Any newly created nodes should be pushed to the stack. Any remaining content should be passed to the next parser
     * in the chain.
     *
     * @param Text $block
     * @return void
     */
    public function parseBlock(Text $block)
    {
        // TODO: Implement parseBlock() method.
    }

}
