<?php

namespace FluxBB\CommonMark\Node;

class Stack implements NodeAcceptorInterface
{

    /**
     * @var Node
     */
    protected $root;


    public function __construct(Node $root)
    {
        $this->root = $root;
        $this->current = $root;
    }

    public function acceptParagraph(Paragraph $paragraph)
    {
        return $this->current = $this->current->acceptParagraph($paragraph);
    }

    public function acceptBlockquote(Blockquote $blockquote)
    {
        return $this->current = $this->current->acceptBlockquote($blockquote);
    }

    public function acceptListBlock(ListBlock $listBlock)
    {
        return $this->current = $this->current->acceptListBlock($listBlock);
    }

    public function acceptHeading(Heading $heading)
    {
        return $this->current = $this->current->acceptHeading($heading);
    }

    public function acceptHorizontalRule(HorizontalRule $horizontalRule)
    {
        return $this->current = $this->current->acceptHorizontalRule($horizontalRule);
    }

    public function acceptHTMLBlock(HTMLBlock $htmlBlock)
    {
        return $this->current = $this->current->acceptHTMLBlock($htmlBlock);
    }

    public function acceptBlankLine(BlankLine $blankLine)
    {
        return $this->current = $this->current->acceptBlankLine($blankLine);
    }

    public function acceptCodeBlock(CodeBlock $codeBlock)
    {
        return $this->current = $this->current->acceptCodeBlock($codeBlock);
    }

}