<?php

namespace FluxBB\CommonMark\Node;

interface NodeAcceptorInterface
{

    public function acceptParagraph(Paragraph $paragraph);

    public function acceptBlockquote(Blockquote $blockquote);

    public function acceptListBlock(ListBlock $listBlock);

    public function acceptHeading(Heading $heading);

    public function acceptHorizontalRule(HorizontalRule $horizontalRule);

    public function acceptHTMLBLock(HTMLBlock $htmlBlock);

    public function acceptBlankLine(BlankLine $blankLine);

    public function acceptCodeBlock(CodeBlock $codeBlock);

}
