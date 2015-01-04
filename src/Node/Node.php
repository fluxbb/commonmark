<?php

namespace FluxBB\CommonMark\Node;

abstract class Node implements NodeAcceptorInterface, InlineNodeAcceptorInterface
{

    /**
     * All inline children of this node.
     *
     * @var Node[]
     */
    protected $inlines = [];

    /**
     * The parent node.
     *
     * @var Node
     */
    protected $parent = null;


    /**
     * Add an inline element.
     *
     * @param Node $inline
     * @return void
     */
    public function addInline(Node $inline)
    {
        $this->inlines[] = $inline;
    }

    /**
     * Return all inline elements.
     *
     * @return Node[]
     */
    public function getInlines()
    {
        return $this->inlines;
    }

    /**
     * Set a node as parent of this node.
     *
     * @param Node $parent
     * @return void
     */
    protected function setParent(Node $parent)
    {
        $this->parent = $parent;
    }

    public function acceptParagraph(Paragraph $paragraph)
    {
        return $this->parent->acceptParagraph($paragraph);
    }

    public function acceptBlockquote(Blockquote $blockquote)
    {
        return $this->parent->acceptBlockquote($blockquote);
    }

    public function acceptListBlock(ListBlock $listBlock)
    {
        return $this->parent->acceptListBlock($listBlock);
    }

    public function acceptHeading(Heading $heading)
    {
        return $this->parent->acceptHeading($heading);
    }

    public function acceptHorizontalRule(HorizontalRule $horizontalRule)
    {
        return $this->parent->acceptHorizontalRule($horizontalRule);
    }

    public function acceptHTMLBlock(HTMLBlock $htmlBlock)
    {
        return $this->parent->acceptHTMLBlock($htmlBlock);
    }

    public function acceptBlankLine(BlankLine $blankLine)
    {
        return $this->parent->acceptBlankLine($blankLine);
    }

    public function acceptCodeBlock(CodeBlock $codeBlock)
    {
        return $this->parent->acceptCodeBlock($codeBlock);
    }

    /**
     * Accept a visit from a node visitor.
     *
     * This method should instrument the visitor to handle this node correctly, and also pass it on to any child nodes.
     *
     * @param NodeVisitorInterface $visitor
     * @return void
     */
    abstract public function visit(NodeVisitorInterface $visitor);

}
