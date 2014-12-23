<?php

namespace FluxBB\Markdown;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Blockquote;
use FluxBB\Markdown\Node\Code;
use FluxBB\Markdown\Node\CodeBlock;
use FluxBB\Markdown\Node\Emphasis;
use FluxBB\Markdown\Node\Heading;
use FluxBB\Markdown\Node\HorizontalRule;
use FluxBB\Markdown\Node\Image;
use FluxBB\Markdown\Node\InlineNodeAcceptorInterface;
use FluxBB\Markdown\Node\Link;
use FluxBB\Markdown\Node\ListBlock;
use FluxBB\Markdown\Node\ListItem;
use FluxBB\Markdown\Node\NodeVisitorInterface;
use FluxBB\Markdown\Node\Paragraph;
use FluxBB\Markdown\Node\HardBreak;
use FluxBB\Markdown\Node\String;
use FluxBB\Markdown\Node\StrongEmphasis;
use FluxBB\Markdown\Parser\AbstractInlineParser;
use FluxBB\Markdown\Parser\Inline\AutolinkParser;
use FluxBB\Markdown\Parser\Inline\CodeSpanParser;
use FluxBB\Markdown\Parser\Inline\EmphasisParser;
use FluxBB\Markdown\Parser\Inline\ImageParser;
use FluxBB\Markdown\Parser\Inline\LineBreakParser;
use FluxBB\Markdown\Parser\Inline\LinkParser;
use FluxBB\Markdown\Parser\Inline\StrongEmphasisParser;
use FluxBB\Markdown\Parser\InlineParserInterface;

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


    public function __construct()
    {
        $this->registerDefaultParsers();

        $this->parser = $this->buildParserStack();
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

    public function enterHeading(Heading $heading)
    {
        $this->parser->parseInline($heading->getText(), $heading);
    }

    public function leaveHeading(Heading $heading)
    {
        return;
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
            new CodeSpanParser(),
            new LineBreakParser(),
            new AutolinkParser(),
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
        $target->addInline(new String($content));
    }

}
