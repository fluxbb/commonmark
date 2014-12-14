<?php

namespace FluxBB\Markdown\Node;

abstract class Node implements NodeAcceptorInterface
{

    /**
     * @var Node[]
     */
    protected $children = [];

    /**
     * @var Node
     */
    protected $parent = null;


    /**
     * Return a string representation of this node's type.
     *
     * @return string
     */
    abstract public function getType();

    /**
     * Convert this node to a string.
     *
     * @return string
     */
    public function toString()
    {
        return $this->getType();
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
     * Merge the given node's children into the current one's.
     *
     * @param Node $sibling
     * @return void
     */
    public function merge(Node $sibling)
    {
        $this->children = array_merge($this->children, $sibling->children);
    }

}
