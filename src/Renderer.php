<?php

namespace FluxBB\Markdown;

use FluxBB\Markdown\Common\Tag;
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
        $this->buffer->append(Tag::inline('hr'))->append("\n");
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
        $this->buffer->append($string->getContent()->escapeHtml());
    }

    public function visitEmphasis(Emphasis $emphasis)
    {
        $tag = Tag::block('em');
        $tag->setText($emphasis->getContent());

        $this->buffer->append($tag);
    }

    public function visitStrongEmphasis(StrongEmphasis $strongEmphasis)
    {
        $tag = Tag::block('strong');
        $tag->setText($strongEmphasis->getContent());

        $this->buffer->append($tag);
    }

    public function visitLink(Link $link)
    {
        $tag = Tag::block('a');
        $tag->setAttribute('href', $link->getHref()->escapeHtml());
        $tag->setText($link->getContent()->escapeHtml());

        $this->buffer->append($tag);
    }

    public function visitImage(Image $image)
    {
        $tag = Tag::inline('img');
        $tag->setAttributes([
            'src' => $image->getSource()->escapeHtml(),
            'alt' => $image->getAltText()->escapeHtml(),
        ]);

        if (! $image->getTitleText()->isEmpty()) {
            $tag->setAttribute('title', $image->getTitleText()->escapeHtml());
        }

        $this->buffer->append($tag);
    }

    public function visitCode(Code $code)
    {
        $tag = Tag::block('code');
        $tag->setText($code->getContent());

        $this->buffer->append($tag);
    }

    public function visitHardBreak(HardBreak $softBreak)
    {
        $this->buffer->append(Tag::inline('br'))->append("\n");
    }

    protected function renderInlineElements(Node $node)
    {
        $children = $node->getInlines();

        foreach ($children as $child) {
            $child->visit($this);
        }
    }

}
