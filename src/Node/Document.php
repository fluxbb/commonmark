<?php

namespace FluxBB\Markdown\Node;

class Document extends Block
{

    public function canContain(Node $other)
    {
        return true;
    }

    public function isOpen()
    {
        return true;
    }

    public function getType()
    {
        return 'document';
    }

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
