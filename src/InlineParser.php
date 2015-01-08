<?php

namespace FluxBB\CommonMark;

use FluxBB\CommonMark\Common\Collection;
use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\InlineNodeAcceptorInterface;
use FluxBB\CommonMark\Parser\AbstractInlineParser;
use FluxBB\CommonMark\Parser\Inline\AutolinkParser;
use FluxBB\CommonMark\Parser\Inline\CodeSpanParser;
use FluxBB\CommonMark\Parser\Inline\EmphasisParser;
use FluxBB\CommonMark\Parser\Inline\ImageParser;
use FluxBB\CommonMark\Parser\Inline\LineBreakParser;
use FluxBB\CommonMark\Parser\Inline\LinkParser;
use FluxBB\CommonMark\Parser\Inline\RawHTMLParser;
use FluxBB\CommonMark\Parser\Inline\StrongEmphasisParser;
use FluxBB\CommonMark\Parser\Inline\TextParser;
use FluxBB\CommonMark\Parser\InlineParserInterface;
use SplQueue;

class InlineParser implements InlineParserInterface
{

    /**
     * @var InlineParserInterface[]
     */
    protected $parsers;

    /**
     * The tip of the parser stack.
     *
     * @var InlineParserInterface
     */
    protected $parser;

    /**
     * @var Collection
     */
    protected $links;

    /**
     * @var Collection
     */
    protected $titles;

    /**
     * @var SplQueue
     */
    protected $queue;


    public function __construct(Collection $links, Collection $titles)
    {
        $this->links = $links;
        $this->titles = $titles;

        $this->registerDefaultParsers();

        $this->parser = $this->buildParserStack();

        $this->queue = new SplQueue();
    }

    public function queue(Text $content, InlineNodeAcceptorInterface $node)
    {
        $this->queue->enqueue([$content, $node]);
    }

    public function parse()
    {
        while (! $this->queue->isEmpty()) {
            list($content, $node) = $this->queue->dequeue();

            $this->parser->parseInline($content, $node);
        }
    }

    public function getReferences()
    {
        return $this->links->keys();
    }

    public function getReferenceUrl($reference)
    {
        return $this->links->get($reference);
    }

    public function getReferenceTitle($reference)
    {
        return $this->titles->exists($reference) ? $this->titles->get($reference) : new Text();
    }

    /**
     * Register all standard parsers.
     *
     * @return void
     */
    protected function registerDefaultParsers()
    {
        $this->parsers = [
            new AutolinkParser(),
            new RawHTMLParser(),
            new CodeSpanParser(),
            new LineBreakParser(),
            new ImageParser(),
            new LinkParser(),
            new StrongEmphasisParser(),
            new EmphasisParser(),
            new TextParser(),
        ];
    }

    /**
     * Build the nested stack of closures that executes the parsers in the correct order.
     *
     * @return InlineParserInterface
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
        return function (InlineParserInterface $stack, AbstractInlineParser $parser) {
            $parser->setNextParser($stack);
            $parser->setContext($this);

            return $parser;
        };
    }

    /**
     * Parse the given content.
     *
     * Any newly created nodes should be appended to the given target. Any remaining content should be passed to the
     * next parser in the chain.
     *
     * @param Text $content
     * @param InlineNodeAcceptorInterface $target
     * @return void
     */
    public function parseInline(Text $content, InlineNodeAcceptorInterface $target)
    {
        // Do nothing. This is just the fallback.
    }

}
