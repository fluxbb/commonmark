<?php

namespace FluxBB\CommonMark\Node;

abstract class Node implements InlineNodeAcceptorInterface
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
