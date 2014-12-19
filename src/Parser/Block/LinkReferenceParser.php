<?php

namespace FluxBB\Markdown\Parser\Block;

use FluxBB\Markdown\Common\Text;
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


    /**
     * Parse the given content.
     *
     * Any newly created nodes should be pushed to the stack. Any remaining content should be passed to the next parser
     * in the chain.
     *
     * @param Text $content
     * @return void
     */
    public function parse(Text $content)
    {
        $content->handle('{
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
        }xm', function (Text $whole, Text $id, Text $url, Text $title = null) {
            $id = $id->lower()->getString();

            $this->links[$id] = $url;

            if ($title) {
                $this->titles[$id] = $title;
            }
        }, function (Text $part) {
            $this->next->parse($part);
        });
    }

}
