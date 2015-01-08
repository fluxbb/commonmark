<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Collection;
use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Parser\AbstractBlockParser;
use SplQueue;

class LinkReferenceParser extends AbstractBlockParser
{

    /**
     * @var Collection
     */
    protected $links = [];

    /**
     * @var Collection
     */
    protected $titles = [];

    /**
     * @var SplQueue
     */
    protected $queue;


    public function __construct(Collection $links, Collection $titles)
    {
        $this->links = $links;
        $this->titles = $titles;

        $this->queue = new SplQueue();
    }

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
                [ ]{0,3}\[(.+)\]:  # id = $1
                  [ \t]*
                  \n?               # maybe *one* newline
                  [ \t]*
                (?|                 # url = $2
                  ([^\s<>]+)            # either without spaces
                  |
                  <([^<>\\n]*)>         # or enclosed in angle brackets
                )
                (?:
                  [ \t]*
                  \n?               # maybe one newline
                  [ \t]*
                    (?<=\s)         # lookbehind for whitespace
                    ["\'(]
                    ([^\n]+?)           # title = $3
                    ["\')]
                    [ \t]*
                )?  # title is optional
                $
            }msx',
            function (Text $whole, Text $id, Text $url, Text $title = null) use ($target) {
                if (! $this->queue->isEmpty()) {
                    $lastPart = $this->queue->dequeue();

                    // If the previous line was not empty, we should not parse this as a link reference
                    if (! $lastPart->match('/(^|\n *)\n$/')) {
                        $lastPart->append($whole);
                        $this->queue->enqueue($lastPart);
                        return;
                    }

                    $this->parsePart($lastPart, $target);
                }

                $id = $id->replace('/[\ \n]+/', ' ')->lower()->getString();

                // Throw away duplicate reference definitions
                if ( ! $this->links->exists($id)) {
                    $url->decodeEntities();

                    // Replace special characters in the URL
                    $url->encodeUrl();

                    $this->links->set($id, $url);

                    if ($title) {
                        $title->decodeEntities();
                        $this->titles->set($id, $title);
                    }
                }
            },
            function (Text $part) {
                if (! $this->queue->isEmpty()) {
                    $lastPart = $this->queue->dequeue();
                    $part->prepend($lastPart);
                }

                $this->queue->enqueue($part);
            }
        );

        if (! $this->queue->isEmpty()) {
            $lastPart = $this->queue->dequeue();
            $this->parsePart($lastPart, $target);
        }
    }

    protected function parsePart(Text $part, Container $target)
    {
        $this->next->parseBlock($part, $target);
    }

}
