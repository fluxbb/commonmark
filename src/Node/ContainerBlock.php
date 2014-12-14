<?php

namespace FluxBB\Markdown\Node;

abstract class ContainerBlock extends Block implements NodeAcceptorInterface
{

    /**
     * @var Node[]
     */
    protected $children = [];


    /**
     * Add a node as child of this node.
     *
     * @param Node $child
     * @return $this
     */
    public function addChild(Node $child)
    {
        $this->children[] = $child;
        $child->setParent($this);

        return $this;
    }

    /**
     * Merge the given node's children into the current one's.
     *
     * @param ContainerBlock $sibling
     * @return void
     */
    public function merge(ContainerBlock $sibling)
    {
        $this->children = array_merge($this->children, $sibling->children);
    }

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
