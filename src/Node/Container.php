<?php

namespace FluxBB\Markdown\Node;

abstract class Container extends Node implements NodeAcceptorInterface
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
     * @param Container $sibling
     * @return void
     */
    public function merge(Container $sibling)
    {
        $this->children = array_merge($this->children, $sibling->children);
    }

    public function acceptParagraph(Paragraph $paragraph)
    {
        return $this->addChild($paragraph);
    }

    public function acceptBlockquote(Blockquote $blockquote)
    {
        $this->addChild($blockquote);

        return $blockquote;
    }

    public function acceptListItem(ListItem $listItem)
    {
        $this->addChild($listItem);

        return $listItem;
    }

    public function acceptHeading(Heading $heading)
    {
        return $this->addChild($heading);
    }

    public function acceptHorizontalRule(HorizontalRule $horizontalRule)
    {
        return $this->addChild($horizontalRule);
    }

    public function acceptBlankLine(BlankLine $blankLine)
    {
        return $this->addChild($blankLine);
    }

    public function acceptCodeBlock(CodeBlock $codeBlock)
    {
        return $this->addChild($codeBlock);
    }

    /**
     * Accept a visit from a node visitor.
     *
     * This method should instrument the visitor to handle this node correctly, and also pass it on to any child nodes.
     *
     * @param NodeVisitorInterface $visitor
     * @return void
     */
    public function visit(NodeVisitorInterface $visitor)
    {
        foreach ($this->children as $child)
        {
            $child->visit($visitor);
        }
    }

}
