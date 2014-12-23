<?php

namespace FluxBB\Markdown;

use FluxBB\Markdown\Common\Collection;
use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Document;
use FluxBB\Markdown\Node\Stack;
use FluxBB\Markdown\Parser\AbstractParser;
use FluxBB\Markdown\Parser\Block\AtxHeaderParser;
use FluxBB\Markdown\Parser\Block\BlockquoteParser;
use FluxBB\Markdown\Parser\Block\CodeBlockParser;
use FluxBB\Markdown\Parser\Block\FencedCodeBlockParser;
use FluxBB\Markdown\Parser\Block\HorizontalRuleParser;
use FluxBB\Markdown\Parser\Block\LinkReferenceParser;
use FluxBB\Markdown\Parser\Block\ListParser;
use FluxBB\Markdown\Parser\Block\ParagraphParser;
use FluxBB\Markdown\Parser\Block\SetextHeaderParser;
use FluxBB\Markdown\Parser\ParserInterface;

class DocumentParser implements ParserInterface
{

    /**
     * @var Collection
     */
    protected $links;

    /**
     * @var Collection
     */
    protected $titles;

    /**
     * @var ParserInterface[]
     */
    protected $parsers = [];

    /**
     * @var Stack
     */
    protected $stack;


    /**
     * Create a parser instance.
     */
    public function __construct()
    {
        $this->links = new Collection();
        $this->titles = new Collection();

        $this->registerDefaultParsers();
    }

    /**
     * Parse the given Markdown text into a document tree.
     *
     * @param string $markdown
     * @return Document
     */
    public function convert($markdown)
    {
        $root = new Document();
        $this->stack = new Stack($root);

        $parser = $this->buildParserStack();

        $text = new Text($markdown);
        $this->prepare($text);

        $parser->parse($text);

        $this->parseInlineContent($root);

        return $root;
    }

    /**
     * Preprocess the text.
     *
     * This unifies line endings and replaces tabs by spaces.
     *
     * @param Text $text
     * @return void
     */
    protected function prepare(Text $text)
    {
        // Unify line endings
        $text->replaceString("\r\n", "\n");
        $text->replaceString("\r", "\n");

        // Replace tabs by spaces
        $text->replace('/(.*?)\t/', function (Text $whole, Text $string) {
            $tabWidth = 4;
            return $string . str_repeat(' ', $tabWidth - $string->getLength() % $tabWidth);
        });
    }

    /**
     * Parse the inline elements of our document tree.
     *
     * @param Document $root
     * @return void
     */
    protected function parseInlineContent(Document $root)
    {
        $inlineParser = new InlineParser;
        $root->visit($inlineParser);
    }

    /**
     * Register all standard parsers.
     *
     * @return void
     */
    protected function registerDefaultParsers()
    {
        $this->parsers = [
            new FencedCodeBlockParser(),
            new CodeBlockParser(),
            new LinkReferenceParser($this->links, $this->titles),
            new BlockquoteParser(),
            new SetextHeaderParser(),
            new HorizontalRuleParser(),
            new ListParser(),
            new AtxHeaderParser(),
            new ParagraphParser(),
        ];
    }

    /**
     * Build the nested stack of closures that executes the parsers in the correct order.
     *
     * @return ParserInterface
     */
    protected function buildParserStack()
    {
        $parsers = array_reverse($this->parsers);

        return array_reduce($parsers, $this->prepareParser(), $this);
    }

    /**
     * Create the closure that returns another closure to be passed to each parser.
     *
     * @return callable
     */
    protected function prepareParser()
    {
        return function (ParserInterface $stack, AbstractParser $parser) {
            $parser->setNextParser($stack);
            $parser->setStack($this->stack);

            return $parser;
        };
    }

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
        // Do nothing. This is just the fallback.
    }
}
