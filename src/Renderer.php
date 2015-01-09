<?php

namespace FluxBB\CommonMark;

use FluxBB\CommonMark\Common\Tag;
use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Block\Blockquote;
use FluxBB\CommonMark\Node\Inline\Code;
use FluxBB\CommonMark\Node\Block\CodeBlock;
use FluxBB\CommonMark\Node\Document;
use FluxBB\CommonMark\Node\Inline\Emphasis;
use FluxBB\CommonMark\Node\Block\Heading;
use FluxBB\CommonMark\Node\Block\HorizontalRule;
use FluxBB\CommonMark\Node\Block\HTMLBlock;
use FluxBB\CommonMark\Node\Inline\Image;
use FluxBB\CommonMark\Node\Inline\Link;
use FluxBB\CommonMark\Node\Block\ListBlock;
use FluxBB\CommonMark\Node\Block\ListItem;
use FluxBB\CommonMark\Node\Node;
use FluxBB\CommonMark\Node\NodeVisitorInterface;
use FluxBB\CommonMark\Node\Block\Paragraph;
use FluxBB\CommonMark\Node\Inline\HardBreak;
use FluxBB\CommonMark\Node\Inline\RawHTML;
use FluxBB\CommonMark\Node\Inline\String;
use FluxBB\CommonMark\Node\Inline\StrongEmphasis;
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
        $this->pushBuffer("\n");
    }

    public function leaveBlockquote(Blockquote $blockquote)
    {
        $tag = Tag::block('blockquote');
        $tag->setText($this->popBuffer());

        $this->buffer->append($tag)->append("\n");
    }

    public function enterListBlock(ListBlock $listBlock)
    {
        $this->pushBuffer("\n");
    }

    public function leaveListBlock(ListBlock $listBlock)
    {
        $type = $listBlock->getType();
        $start = $listBlock->getStart();

        if ($type == 'ol') {
            $tag = Tag::block('ol');
            if ($start > 1) {
                $tag->setAttribute('start', $start);
            }
        } else {
            $tag = Tag::block('ul');
        }

        $tag->setText($this->popBuffer());

        $this->buffer->append($tag)->append("\n");
    }

    public function enterListItem(ListItem $listItem)
    {
        $this->pushBuffer("\n");
    }

    public function leaveListItem(ListItem $listItem)
    {
        $content = $this->popBuffer();

        if ($listItem->isTerse()) {
            $content = $listItem->getContent();
        }

        $tag = Tag::block('li')->setText($content);

        $this->buffer->append($tag)->append("\n");
    }

    public function visitHeading(Heading $heading)
    {
        $tag = Tag::block('h' . $heading->getLevel());

        $this->fillWithInlineElements($tag, $heading);

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
        $this->fillWithInlineElements($tag, $emphasis);

        $this->buffer->append($tag);
    }

    public function visitStrongEmphasis(StrongEmphasis $strongEmphasis)
    {
        $tag = Tag::block('strong');
        $this->fillWithInlineElements($tag, $strongEmphasis);

        $this->buffer->append($tag);
    }

    public function visitLink(Link $link)
    {
        $tag = Tag::block('a');
        $tag->setAttribute('href', $link->getHref()->escapeHtml());

        $this->fillWithInlineElements($tag, $link);

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

    protected function fillWithInlineElements(Tag $tag, Node $node)
    {
        $this->pushBuffer();
        $this->renderInlineElements($node);
        $tag->setText($this->popBuffer());
    }

    protected function pushBuffer($text = '')
    {
        $this->stack->push($this->buffer);

        $this->buffer = new Text($text);
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
