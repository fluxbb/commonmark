<?php

namespace FluxBB\Markdown\Node;

use FluxBB\Markdown\Common\Text;

class ListItem extends Container implements NodeAcceptorInterface
{

    protected $content;


    public function __construct(Text $content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function acceptParagraph(Paragraph $paragraph)
    {
        $this->addChild($paragraph);

        return $paragraph;
    }

    public function acceptBlockquote(Blockquote $blockquote)
    {
        return $this->parent->acceptBlockquote($blockquote);
    }

    public function acceptBlankLine(BlankLine $blankLine)
    {
        return $this->parent;
    }

    public function acceptHorizontalRule(HorizontalRule $horizontalRule)
    {
        return $this->parent->acceptHorizontalRule($horizontalRule);
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->visitListItem($this);
    }

}
