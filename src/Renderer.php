<?php

namespace FluxBB\CommonMark;

use FluxBB\CommonMark\Common\Tag;
use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Blockquote;
use FluxBB\CommonMark\Node\Code;
use FluxBB\CommonMark\Node\CodeBlock;
use FluxBB\CommonMark\Node\Document;
use FluxBB\CommonMark\Node\Emphasis;
use FluxBB\CommonMark\Node\Heading;
use FluxBB\CommonMark\Node\HorizontalRule;
use FluxBB\CommonMark\Node\HTMLBlock;
use FluxBB\CommonMark\Node\Image;
use FluxBB\CommonMark\Node\Link;
use FluxBB\CommonMark\Node\ListBlock;
use FluxBB\CommonMark\Node\ListItem;
use FluxBB\CommonMark\Node\Node;
use FluxBB\CommonMark\Node\NodeVisitorInterface;
use FluxBB\CommonMark\Node\Paragraph;
use FluxBB\CommonMark\Node\HardBreak;
use FluxBB\CommonMark\Node\RawHTML;
use FluxBB\CommonMark\Node\String;
use FluxBB\CommonMark\Node\StrongEmphasis;
use SplStack;

class Renderer implements NodeVisitorInterface
{

    /**
     * @var Text
     */
    protected $buffer;

    /**
     * @var SplStack
     */
    protected $stack;


    public function __construct()
    {
        $this->stack = new SplStack();
    }

    public function render(Document $document)
    {
        $this->buffer = new Text();

        $document->visit($this);

        return $this->buffer;
    }

    public function enterParagraph(Paragraph $paragraph)
    {
        $this->pushBuffer();
        $this->renderInlineElements($paragraph);
    }

    public function leaveParagraph(Paragraph $paragraph)
    {
        $tag = Tag::block('p');
        $tag->setText($this->popBuffer());

        $this->buffer->append($tag)->append("\n");
    }

    public function enterBlockquote(Blockquote $blockquote)
    {
        // TODO: Linebreak after opening tag
        $this->pushBuffer();
    }

    public function leaveBlockquote(Blockquote $blockquote)
    {
        $tag = Tag::block('blockquote');
        $tag->setText($this->popBuffer());

        $this->buffer->append($tag)->append("\n");
    }

    public function enterListBlock(ListBlock $listBlock)
    {
        // TODO: Linebreak after opening tag
        $this->pushBuffer();
    }

    public function leaveListBlock(ListBlock $listBlock)
    {
        $tag = Tag::block('ul')->setText($this->popBuffer());

        $this->buffer->append($tag)->append("\n");
    }

    public function enterListItem(ListItem $listItem)
    {
        $this->pushBuffer();
    }

    public function leaveListItem(ListItem $listItem)
    {
        $tag = Tag::block('li')->setText($this->popBuffer());

        $this->buffer->append($tag)->append("\n");
    }

    public function visitHeading(Heading $heading)
    {
        $tag = Tag::block('h' . $heading->getLevel());

        $this->pushBuffer();
        $this->renderInlineElements($heading);
        $tag->setText($this->popBuffer());

        $this->buffer->append($tag)->append("\n");
    }

    public function visitHorizontalRule(HorizontalRule $horizontalRule)
    {
        $this->buffer->append(Tag::inline('hr'))->append("\n");
    }

    public function visitHTMLBlock(HTMLBlock $htmlBlock)
    {
        $this->buffer->append($htmlBlock->getContent())->append("\n");
    }

    public function visitCodeBlock(CodeBlock $codeBlock)
    {
        $preTag = Tag::block('pre');
        $codeTag = Tag::block('code');

        if ($codeBlock->hasLanguage()) {
            $codeTag->setAttribute('class', 'language-' . $codeBlock->getLanguage());
        }

        $codeTag->setText($codeBlock->getContent()->escapeHtml());
        $preTag->setText($codeTag->render());

        $this->buffer->append($preTag)->append("\n");
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

        if (! $link->getTitleText()->isEmpty()) {
            $tag->setAttribute('title', $link->getTitleText()->escapeHtml());
        }

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

    public function visitRawHTML(RawHTML $rawHtml)
    {
        $this->buffer->append($rawHtml->getContent());
    }

    public function visitHardBreak(HardBreak $softBreak)
    {
        $this->buffer->append(Tag::inline('br'))->append("\n");
    }

    protected function pushBuffer()
    {
        $this->stack->push($this->buffer);

        $this->buffer = new Text();
    }

    protected function popBuffer()
    {
        $buffer = $this->buffer;

        $this->buffer = $this->stack->pop();

        return $buffer;
    }

    protected function renderInlineElements(Node $node)
    {
        $children = $node->getInlines();

        foreach ($children as $child) {
            $child->visit($this);
        }
    }

}
