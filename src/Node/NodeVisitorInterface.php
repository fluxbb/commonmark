<?php

namespace FluxBB\CommonMark\Node;

interface NodeVisitorInterface
{

    public function enterParagraph(Paragraph $paragraph);

    public function leaveParagraph(Paragraph $paragraph);

    public function enterBlockquote(Blockquote $blockquote);

    public function leaveBlockquote(Blockquote $blockquote);

    public function enterListBlock(ListBlock $listBlock);

    public function leaveListBlock(ListBlock $listBlock);

    public function enterListItem(ListItem $listItem);

    public function leaveListItem(ListItem $listItem);

    public function visitHeading(Heading $heading);

    public function visitHorizontalRule(HorizontalRule $horizontalRule);

    public function visitHTMLBlock(HTMLBlock $htmlBlock);

    public function visitCodeBlock(CodeBlock $codeBlock);

    public function visitString(String $string);

    public function visitEmphasis(Emphasis $emphasis);

    public function visitStrongEmphasis(StrongEmphasis $strongEmphasis);

    public function visitLink(Link $link);

    public function visitImage(Image $image);

    public function visitCode(Code $code);

    public function visitHardBreak(HardBreak $softBreak);

}
