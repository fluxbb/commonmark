<?php

namespace FluxBB\Markdown\Node;

interface NodeVisitorInterface
{

    public function enterParagraph(Paragraph $paragraph);

    public function leaveParagraph(Paragraph $paragraph);

    public function enterBlockquote(Blockquote $blockquote);

    public function leaveBlockquote(Blockquote $blockquote);

    public function visitListItem(ListItem $listItem);

    public function enterHeading(Heading $heading);

    public function leaveHeading(Heading $heading);

    public function visitHorizontalRule(HorizontalRule $horizontalRule);

    public function visitCodeBlock(CodeBlock $codeBlock);

    public function visitString(String $string);

    public function visitEmphasis(Emphasis $emphasis);

    public function visitStrongEmphasis(StrongEmphasis $strongEmphasis);

}
