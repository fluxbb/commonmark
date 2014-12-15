<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Node;

class LinkReferenceParser implements ParserInterface
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

}
