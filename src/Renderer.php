<?php

namespace FluxBB\Markdown;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Blockquote;
use FluxBB\Markdown\Node\CodeBlock;
use FluxBB\Markdown\Node\Document;
use FluxBB\Markdown\Node\Heading;
use FluxBB\Markdown\Node\HorizontalRule;
use FluxBB\Markdown\Node\NodeVisitorInterface;
use FluxBB\Markdown\Node\Paragraph;

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
            ->append('<p>')
            ->append($paragraph->getText());
    }

    public function leaveParagraph(Paragraph $paragraph)
    {
        $this->buffer->append('</p>');
    }

    public function enterBlockquote(Blockquote $blockquote)
    {
        $this->buffer->append('<blockquote>');
    }

    public function leaveBlockquote(Blockquote $blockquote)
    {
        $this->buffer->append('</blockquote>');
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
        $this->buffer->append('</h')->append($heading->getLevel())->append('>');
    }

    public function visitHorizontalRule(HorizontalRule $horizontalRule)
    {
        $this->buffer->append('<hr />');
    }

    public function visitCodeBlock(CodeBlock $codeBlock)
    {
        $this->buffer
            ->append('<pre><code>')
            ->append($codeBlock->getContent())
            ->append('</code></pre>');
    }

}
