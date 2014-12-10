<?php

namespace FluxBB\Markdown;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Document;
use FluxBB\Markdown\Node\Node;
use FluxBB\Markdown\Parser\BlockquoteParser;
use FluxBB\Markdown\Parser\EmptyLineParser;
use FluxBB\Markdown\Parser\HeaderParser;
use FluxBB\Markdown\Parser\ParagraphParser;
use FluxBB\Markdown\Parser\ParserInterface;

class DocumentParser
{

    /**
     * @var ParserInterface[]
     */
    protected $parsers = [];


    /**
     * Create a parser instance.
     */
    public function __construct()
    {
        $this->registerDefaultParsers();
    }

    /**
     * Parse the given Markdown text into a document tree.
     *
     * @param string $markdown
     * @return Document
     */
    public function parse($markdown)
    {
        $target = $root = new Document();
        $parser = $this->buildParserStack();

        $lines = explode("\n", $markdown);
        foreach ($lines as $line) {
            $target = call_user_func($parser, new Text($line), $target);
        }

        return $root;
    }

    /**
     * Register all standard parsers.
     *
     * @return void
     */
    protected function registerDefaultParsers()
    {
        $this->parsers = [
            new EmptyLineParser(),
            new BlockquoteParser(),
            new HeaderParser(),
            new ParagraphParser(),
        ];
    }

    /**
     * Build the nested stack of closures that executes the parsers in the correct order.
     *
     * @return callable
     */
    protected function buildParserStack()
    {
        $parsers = array_reverse($this->parsers);
        $initial = $this->getInitialClosure();

        return array_reduce($parsers, $this->getParserClosure(), $initial);
    }

    /**
     * Create the closure that returns another closure to be passed to each parser.
     *
     * @return callable
     */
    protected function getParserClosure()
    {
        return function ($stack, ParserInterface $parser) {
            return function (Text $line, Node $target) use ($stack, $parser) {
                return $parser->parseLine($line, $target, $stack);
            };
        };
    }

    /**
     * Create the fallback closure that simply returns the target node and throws away any content.
     *
     * @return callable
     */
    protected function getInitialClosure()
    {
        return function (Text $line, Node $target) {
            return $target;
        };
    }

}
