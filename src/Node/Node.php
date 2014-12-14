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


    abstract public function getType();

    public function toString()
    {
        return $this->getType();
    }

    abstract public function visit(NodeVisitorInterface $visitor);

    public function setParent(Node $parent)
    {
        $this->parent = $parent;
    }

    public function getChildNodes()
    {
        return $this->children;
    }

    public function addChild(Node $child)
    {
        $this->children[] = $child;
        $child->setParent($this);

        return $this;
    }

    public function removeChild(Node $child)
    {
        $this->children = array_filter($this->children, function (Node $element) use ($child) {
            return $child != $element;
        });
    }

    public function merge(Node $sibling)
    {
        $this->children = array_merge($this->children, $sibling->children);
    }

    /**
     * @return Node
     */
    public function getParent()
    {
        return $this->parent;
    }

}
