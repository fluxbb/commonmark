<?php

namespace FluxBB\Markdown\Node;

interface NodeAcceptorInterface
{

    public function acceptParagraph(Paragraph $paragraph);

    public function acceptBlockquote(Blockquote $blockquote);

    public function acceptListItem(ListItem $listItem);

    public function acceptHeading(Heading $heading);

    public function acceptHorizontalRule(HorizontalRule $horizontalRule);

    public function acceptBlankLine(BlankLine $blankLine);

    public function acceptCodeBlock(CodeBlock $codeBlock);

}
