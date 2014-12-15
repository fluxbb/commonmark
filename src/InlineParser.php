<?php

namespace FluxBB\Markdown;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Event\EmitterAwareInterface;
use FluxBB\Markdown\Extension\Core\CodeExtension;
use FluxBB\Markdown\Extension\Core\EscaperExtension;
use FluxBB\Markdown\Extension\Core\ImageExtension;
use FluxBB\Markdown\Extension\Core\InlineStyleExtension;
use FluxBB\Markdown\Extension\Core\LinkExtension;
use FluxBB\Markdown\Extension\Core\WhitespaceExtension;
use FluxBB\Markdown\Node\Blockquote;
use FluxBB\Markdown\Node\CodeBlock;
use FluxBB\Markdown\Node\Emphasis;
use FluxBB\Markdown\Node\Heading;
use FluxBB\Markdown\Node\HorizontalRule;
use FluxBB\Markdown\Node\ListItem;
use FluxBB\Markdown\Node\Node;
use FluxBB\Markdown\Node\NodeVisitorInterface;
use FluxBB\Markdown\Node\Paragraph;
use FluxBB\Markdown\Node\HardBreak;
use FluxBB\Markdown\Node\String;
use FluxBB\Markdown\Node\StrongEmphasis;
use FluxBB\Markdown\Renderer\InlineRenderer;
use FluxBB\Markdown\Renderer\RendererAwareInterface;

class InlineParser implements NodeVisitorInterface
{

    /**
     * @var Markdown
     */
    protected $markdown;

    /**
     * @var \FluxBB\Markdown\Renderer\RendererInterface
     */
    protected $renderer;

    /**
     * @var \FluxBB\Markdown\Common\Text[]
     */
    protected $blobs;


    public function __construct()
    {
        $this->renderer = new InlineRenderer($this);
        $this->markdown = new Markdown($this->renderer);

        $this->registerExtensions();
    }

    public function enterParagraph(Paragraph $paragraph)
    {
        $this->parseInline($paragraph, $paragraph->getText());
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

    public function visitListItem(ListItem $listItem)
    {
        return;
    }

    public function enterHeading(Heading $heading)
    {
        return;
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

    public function visitHardBreak(HardBreak $softBreak)
    {
        return;
    }

    public function addBlob($blob)
    {
        $this->blobs[] = $blob;
    }

    protected function parseInline(Node $node, Text $text)
    {
        $this->blobs = [];
        $this->markdown->emit('inline', [$text]);

        $text->split('/\\0/')->each(function (Text $part) use ($node) {
            $node->addInline(new String($part->getString()));
            if (count($this->blobs)) {
                $blob = array_shift($this->blobs);
                $node->addInline(is_string($blob) ? new String($blob) : $blob);
            }
        });
    }

    private function registerExtensions()
    {
        foreach ($this->getExtensions() as $extension) {
            if ($extension instanceof RendererAwareInterface) {
                $extension->setRenderer($this->renderer);
            }

            if ($extension instanceof EmitterAwareInterface) {
                $extension->setEmitter($this->markdown);
            }

            $extension->register($this->markdown);
        }
    }

    /**
     * @return \FluxBB\Markdown\Extension\ExtensionInterface[]
     */
    private function getExtensions()
    {
        return [
            new WhitespaceExtension(),
            new LinkExtension(),
            new CodeExtension(),
            new ImageExtension(),
            new InlineStyleExtension(),
            new EscaperExtension(),
        ];
    }

}
