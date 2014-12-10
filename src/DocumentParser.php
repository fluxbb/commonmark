<?php

namespace FluxBB\Markdown;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Document;
use FluxBB\Markdown\Node\Node;
use FluxBB\Markdown\Parser\BlankLineParser;
use FluxBB\Markdown\Parser\BlockquoteParser;
use FluxBB\Markdown\Parser\HeaderParser;
use FluxBB\Markdown\Parser\HorizontalRuleParser;
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

        $this->getLines(new Text($markdown))->reduce(function ($target, Text $line) use ($parser) {
            return call_user_func($parser, $line, $target);
        }, $target);

        return $root;
    }

    /**
     * Preprocess the text and return a collection of lines.
     *
     * @param Text $text
     * @return \FluxBB\Markdown\Common\Collection
     */
    protected function getLines(Text $text)
    {
        // Unify line endings
        $text->replaceString("\r\n", "\n");
        $text->replaceString("\r", "\n");

        $text->append("\n\n");

        // TODO: Replace tabs by spaces

        // Trim empty lines
        $text->replace('/^[ \t]+$/m', '');

        return $text->split('/\n/');
    }

    /**
     * Register all standard parsers.
     *
     * @return void
     */
    protected function registerDefaultParsers()
    {
        $this->parsers = [
            new BlankLineParser(),
            new BlockquoteParser(),
            new HorizontalRuleParser(),
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
