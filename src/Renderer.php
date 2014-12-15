<?php

namespace FluxBB\Markdown;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Blockquote;
use FluxBB\Markdown\Node\CodeBlock;
use FluxBB\Markdown\Node\Document;
use FluxBB\Markdown\Node\Emphasis;
use FluxBB\Markdown\Node\Heading;
use FluxBB\Markdown\Node\HorizontalRule;
use FluxBB\Markdown\Node\ListItem;
use FluxBB\Markdown\Node\Node;
use FluxBB\Markdown\Node\NodeVisitorInterface;
use FluxBB\Markdown\Node\Paragraph;
use FluxBB\Markdown\Node\String;
use FluxBB\Markdown\Node\StrongEmphasis;

class Renderer implements NodeVisitorInterface
{

    /**
     * @var Text
     */
    protected $buffer;

    public function render(Document $document)
    {
        $this->buffer = new Text();

        $document->visit($this);

        return $this->buffer;
    }

    public function enterParagraph(Paragraph $paragraph)
    {
        $this->buffer
            ->append('<p>');

        $this->renderInlineElements($paragraph);
    }

    public function leaveParagraph(Paragraph $paragraph)
    {
        $this->buffer->append("</p>\n");
    }

    public function enterBlockquote(Blockquote $blockquote)
    {
        $this->buffer->append("<blockquote>\n");
    }

    public function leaveBlockquote(Blockquote $blockquote)
    {
        $this->buffer->append("</blockquote>\n");
    }

    public function visitListItem(ListItem $listItem)
    {
        $this->buffer
            ->append("<ul>\n")
            ->append('<li>')
            ->append($listItem->getContent())
            ->append("</li>\n")
            ->append("</ul>\n");
    }

    public function enterHeading(Heading $heading)
    {
        $this->buffer
            ->append('<h')
            ->append($heading->getLevel())
            ->append('>')
            ->append($heading->getText());
    }

    public function leaveHeading(Heading $heading)
    {
        $this->buffer
            ->append('</h')
            ->append($heading->getLevel())
            ->append('>')
            ->append("\n");
    }

    public function visitHorizontalRule(HorizontalRule $horizontalRule)
    {
        $this->buffer->append("<hr />\n");
    }

    public function visitCodeBlock(CodeBlock $codeBlock)
    {
        $this->buffer
            ->append('<pre><code>')
            ->append($codeBlock->getContent()->escapeHtml())
            ->append('</code></pre>')
            ->append("\n");
    }

    public function visitString(String $string)
    {
        $this->buffer->append($string->getContent());
    }

    public function visitEmphasis(Emphasis $emphasis)
    {
        $this->buffer
            ->append('<em>')
            ->append($emphasis->getContent())
            ->append('</em>');
    }

    public function visitStrongEmphasis(StrongEmphasis $strongEmphasis)
    {
        $this->buffer
            ->append('<strong>')
            ->append($strongEmphasis->getContent())
            ->append('</strong>');
    }

    protected function renderInlineElements(Node $node)
    {
        $children = $node->getInlines();

        foreach ($children as $child) {
            $child->visit($this);
        }
    }

}
