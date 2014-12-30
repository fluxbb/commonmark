<?php

namespace FluxBB\CommonMark;

use FluxBB\CommonMark\Common\Collection;
use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Blockquote;
use FluxBB\CommonMark\Node\Code;
use FluxBB\CommonMark\Node\CodeBlock;
use FluxBB\CommonMark\Node\Emphasis;
use FluxBB\CommonMark\Node\Heading;
use FluxBB\CommonMark\Node\HorizontalRule;
use FluxBB\CommonMark\Node\Image;
use FluxBB\CommonMark\Node\InlineNodeAcceptorInterface;
use FluxBB\CommonMark\Node\Link;
use FluxBB\CommonMark\Node\ListBlock;
use FluxBB\CommonMark\Node\ListItem;
use FluxBB\CommonMark\Node\NodeVisitorInterface;
use FluxBB\CommonMark\Node\Paragraph;
use FluxBB\CommonMark\Node\HardBreak;
use FluxBB\CommonMark\Node\String;
use FluxBB\CommonMark\Node\StrongEmphasis;
use FluxBB\CommonMark\Parser\AbstractInlineParser;
use FluxBB\CommonMark\Parser\Inline\AutolinkParser;
use FluxBB\CommonMark\Parser\Inline\CodeSpanParser;
use FluxBB\CommonMark\Parser\Inline\EmphasisParser;
use FluxBB\CommonMark\Parser\Inline\ImageParser;
use FluxBB\CommonMark\Parser\Inline\LineBreakParser;
use FluxBB\CommonMark\Parser\Inline\LinkParser;
use FluxBB\CommonMark\Parser\Inline\StrongEmphasisParser;
use FluxBB\CommonMark\Parser\InlineParserInterface;

class InlineParser implements NodeVisitorInterface, InlineParserInterface
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


    public function __construct(Collection $links, Collection $titles)
    {
        $this->links = $links;
        $this->titles = $titles;

        $this->registerDefaultParsers();

        $this->parser = $this->buildParserStack();
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

    public function enterParagraph(Paragraph $paragraph)
    {
        $this->parser->parseInline($paragraph->getText(), $paragraph);
    }

    public function leaveParagraph(Paragraph $paragraph)
    {
        return;
    }

    public function enterBlockquote(Blockquote $blockquote)
    {
        return;
    }

    public function leaveBlockquote(Blockquote $blockquote)
    {
        return;
    }

    public function enterListBlock(ListBlock $listBlock)
    {
        return;
    }

    public function leaveListBlock(ListBlock $listBlock)
    {
        return;
    }

    public function enterListItem(ListItem $listItem)
    {
        return;
    }

    public function leaveListItem(ListItem $listItem)
    {
        return;
    }

    public function visitHeading(Heading $heading)
    {
        $this->parser->parseInline($heading->getText(), $heading);
    }

    public function visitHorizontalRule(HorizontalRule $horizontalRule)
    {
        return;
    }

    public function visitCodeBlock(CodeBlock $codeBlock)
    {
        return;
    }

    public function visitString(String $string)
    {
        return;
    }

    public function visitEmphasis(Emphasis $emphasis)
    {
        return;
    }

    public function visitStrongEmphasis(StrongEmphasis $strongEmphasis)
    {
        return;
    }

    public function visitLink(Link $link)
    {
        return;
    }

    public function visitImage(Image $image)
    {
        return;
    }

    public function visitCode(Code $code)
    {
        return;
    }

    public function visitHardBreak(HardBreak $softBreak)
    {
        return;
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
            new CodeSpanParser(),
            new LineBreakParser(),
            new ImageParser(),
            new LinkParser(),
            new StrongEmphasisParser(),
            new EmphasisParser(),
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
        $content->decodeEntities();
        $target->addInline(new String($content));
    }

}
