<?php

namespace FluxBB\Markdown;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Blockquote;
use FluxBB\Markdown\Node\Code;
use FluxBB\Markdown\Node\CodeBlock;
use FluxBB\Markdown\Node\Document;
use FluxBB\Markdown\Node\Emphasis;
use FluxBB\Markdown\Node\Heading;
use FluxBB\Markdown\Node\HorizontalRule;
use FluxBB\Markdown\Node\Image;
use FluxBB\Markdown\Node\Link;
use FluxBB\Markdown\Node\ListBlock;
use FluxBB\Markdown\Node\ListItem;
use FluxBB\Markdown\Node\Node;
use FluxBB\Markdown\Node\NodeVisitorInterface;
use FluxBB\Markdown\Node\Paragraph;
use FluxBB\Markdown\Node\HardBreak;
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

    public function enterListBlock(ListBlock $listBlock)
    {
        $this->buffer->append("<ul>\n");
    }

    public function leaveListBlock(ListBlock $listBlock)
    {
        $this->buffer->append("</ul>\n");
    }

    public function enterListItem(ListItem $listItem)
    {
        $this->buffer->append('<li>');
    }

    public function leaveListItem(ListItem $listItem)
    {
        $this->buffer->append("</li>\n");
    }

    public function enterHeading(Heading $heading)
    {
        $this->buffer
            ->append('<h')
            ->append($heading->getLevel())
            ->append('>');

        $this->renderInlineElements($heading);
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
            ->append('<pre><code')
            ->append($codeBlock->hasLanguage() ? ' class="language-' . $codeBlock->getLanguage() . '">' : '>')
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

    public function visitLink(Link $link)
    {
        $this->buffer
            ->append('<a href="')
            ->append($link->getHref())
            ->append('">')
            ->append($link->getContent())
            ->append('</a>');
    }

    public function visitImage(Image $image)
    {
        $this->buffer
            ->append('<img src="')
            ->append($image->getSource())
            ->append('" />');
    }

    public function visitCode(Code $code)
    {
        $this->buffer
            ->append('<code>')
            ->append($code->getContent())
            ->append('</code>');
    }

    public function visitHardBreak(HardBreak $softBreak)
    {
        $this->buffer->append('<br />');
    }

    protected function renderInlineElements(Node $node)
    {
        $children = $node->getInlines();

        foreach ($children as $child) {
            $child->visit($this);
        }
    }

}
