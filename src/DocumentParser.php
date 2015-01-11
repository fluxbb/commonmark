<?php

namespace FluxBB\CommonMark;

use FluxBB\CommonMark\Common\Collection;
use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Parser\AbstractBlockParser;
use FluxBB\CommonMark\Parser\Block\AtxHeaderParser;
use FluxBB\CommonMark\Parser\Block\BlockquoteParser;
use FluxBB\CommonMark\Parser\Block\CodeBlockParser;
use FluxBB\CommonMark\Parser\Block\FencedCodeBlockParser;
use FluxBB\CommonMark\Parser\Block\HorizontalRuleParser;
use FluxBB\CommonMark\Parser\Block\HTMLBlockParser;
use FluxBB\CommonMark\Parser\Block\LinkReferenceParser;
use FluxBB\CommonMark\Parser\Block\ListParser;
use FluxBB\CommonMark\Parser\Block\ParagraphParser;
use FluxBB\CommonMark\Parser\Block\SetextHeaderParser;
use FluxBB\CommonMark\Parser\BlockParserInterface;

class DocumentParser implements BlockParserInterface
{

    const VERSION = '1.0-dev';


    /**
     * @var Collection
     */
    protected $links;

    /**
     * @var Collection
     */
    protected $titles;

    /**
     * @var BlockParserInterface[]
     */
    protected $parsers = [];

    /**
     * @var InlineParser
     */
    protected $inlineParser;


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
     * @return Container
     */
    public function convert($markdown)
    {
        $root = new Container();

        $this->inlineParser = new InlineParser($this->links, $this->titles);
        $parser = $this->buildParserStack();

        $text = new Text($markdown);
        $this->prepare($text);

        $parser->parseBlock($text, $root);

        $this->inlineParser->parse();

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
     * Register all standard parsers.
     *
     * @return void
     */
    protected function registerDefaultParsers()
    {
        $this->parsers = [
            new HTMLBlockParser(),
            new FencedCodeBlockParser(),
            new SetextHeaderParser(),
            new AtxHeaderParser(),
            new BlockquoteParser(),
            new ListParser(),
            new HorizontalRuleParser(),
            new CodeBlockParser(),
            new LinkReferenceParser($this->links, $this->titles),
            new ParagraphParser(),
        ];
    }

    /**
     * Build the nested stack of closures that executes the parsers in the correct order.
     *
     * @return BlockParserInterface
     */
    protected function buildParserStack()
    {
        $parsers = array_reverse($this->parsers);

        return array_reduce($parsers, $this->prepareParser(reset($this->parsers)), $this);
    }

    /**
     * Create the closure that returns another closure to be passed to each parser.
     *
     * @param BlockParserInterface $first
     * @return callable
     */
    protected function prepareParser(BlockParserInterface $first)
    {
        return function (BlockParserInterface $stack, AbstractBlockParser $parser) use ($first) {
            $parser->setNextParser($stack);
            $parser->setFirstParser($first);
            $parser->setInlineParser($this->inlineParser);

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
     * @param Container $target
     * @return void
     */
    public function parseBlock(Text $content, Container $target)
    {
        // Do nothing. This is just the fallback.
    }
}
