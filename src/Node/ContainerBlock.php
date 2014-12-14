<?php

namespace FluxBB\Markdown\Node;

abstract class ContainerBlock extends Block implements NodeAcceptorInterface
{

    public function acceptParagraph(Paragraph $paragraph)
    {
        $this->addChild($paragraph);

        return $this;
    }

    public function acceptBlockquote(Blockquote $blockquote)
    {
        $this->addChild($blockquote);

        return $this;
    }

    public function acceptHeading(Heading $heading)
    {
        $this->addChild($heading);

        return $this;
    }

    public function acceptHorizontalRule(HorizontalRule $horizontalRule)
    {
        $this->addChild($horizontalRule);

        return $this;
    }

    public function acceptBlankLine(BlankLine $blankLine)
    {
        $this->addChild($blankLine);

        return $this;
    }

    public function acceptCodeBlock(CodeBlock $codeBlock)
    {
        $this->addChild($codeBlock);

        return $this;
    }

}
