<?php

namespace FluxBB\CommonMark\Node;

class Document extends Container
{

    public function acceptParagraph(Paragraph $paragraph)
    {
        $this->addChild($paragraph);

        return $paragraph;
    }

    public function acceptBlockquote(Blockquote $blockquote)
    {
        $this->addChild($blockquote);

        return $blockquote;
    }

    public function acceptHeading(Heading $heading)
    {
        $this->addChild($heading);

        return $heading;
    }

    public function acceptHorizontalRule(HorizontalRule $horizontalRule)
    {
        $this->addChild($horizontalRule);

        return $horizontalRule;
    }

    public function acceptBlankLine(BlankLine $blankLine)
    {
        return $this;
    }

    public function acceptCodeBlock(CodeBlock $codeBlock)
    {
        $this->addChild($codeBlock);

        return $codeBlock;
    }

}
