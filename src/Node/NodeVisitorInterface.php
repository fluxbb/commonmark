<?php

namespace FluxBB\CommonMark\Node;

use FluxBB\CommonMark\Node\Block\Blockquote;
use FluxBB\CommonMark\Node\Block\CodeBlock;
use FluxBB\CommonMark\Node\Block\Heading;
use FluxBB\CommonMark\Node\Block\HorizontalRule;
use FluxBB\CommonMark\Node\Block\HTMLBlock;
use FluxBB\CommonMark\Node\Block\ListBlock;
use FluxBB\CommonMark\Node\Block\ListItem;
use FluxBB\CommonMark\Node\Block\Paragraph;
use FluxBB\CommonMark\Node\Inline\Code;
use FluxBB\CommonMark\Node\Inline\Emphasis;
use FluxBB\CommonMark\Node\Inline\HardBreak;
use FluxBB\CommonMark\Node\Inline\Image;
use FluxBB\CommonMark\Node\Inline\Link;
use FluxBB\CommonMark\Node\Inline\RawHTML;
use FluxBB\CommonMark\Node\Inline\String;
use FluxBB\CommonMark\Node\Inline\StrongEmphasis;

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

    public function visitRawHTML(RawHTML $rawHtml);

    public function visitHardBreak(HardBreak $softBreak);

}
