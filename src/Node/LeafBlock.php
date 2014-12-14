<?php

namespace FluxBB\Markdown\Node;

abstract class LeafBlock extends Block implements NodeAcceptorInterface
{

    public function acceptParagraph(Paragraph $paragraph)
    {
        return $this->parent->acceptParagraph($paragraph);
    }

    public function acceptBlockquote(Blockquote $blockquote)
    {
        return $this->parent->acceptBlockquote($blockquote);
    }

    public function acceptHeading(Heading $heading)
    {
        return $this->parent->acceptHeading($heading);
    }

    public function acceptHorizontalRule(HorizontalRule $horizontalRule)
    {
        return $this->parent->acceptHorizontalRule($horizontalRule);
    }

    public function acceptBlankLine(BlankLine $blankLine)
    {
        return $this->parent->acceptBlankLine($blankLine);
    }

    public function acceptCodeBlock(CodeBlock $codeBlock)
    {
        return $this->parent->acceptCodeBlock($codeBlock);
    }

}
