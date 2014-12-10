<?php

namespace FluxBB\Markdown;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\Blockquote;
use FluxBB\Markdown\Node\Document;
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

}
