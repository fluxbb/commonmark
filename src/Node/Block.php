<?php

namespace FluxBB\Markdown\Node;

abstract class Block extends Node implements NodeAcceptorInterface
{

    protected $open = true;


    abstract public function canContain(Node $other);

    public function toString()
    {
        return ($this->isOpen() ? '-> ' : '') . parent::toString();
    }

    public function push(Node $child)
    {
        if ($this->isOpen()) {
            if ($this->canContain($child)) {
                $this->addChild($child);
                return;
            } else {
                $this->close();
            }
        } else {
            $this->getParent()->push($child);
        }
    }

    public function isOpen()
    {
        return $this->open;
    }

    public function close()
    {
        $this->open = false;
    }

    protected function closeAndBubble(Node $node)
    {
        $this->close();
        return $this->parent->accept($node);
    }

    /*
     * Node acceptor methods
     */

    public function acceptParagraph(Paragraph $paragraph)
    {
        return $this->closeAndBubble($paragraph);
    }

    public function acceptBlockquote(Blockquote $blockquote)
    {
        return $this->closeAndBubble($blockquote);
    }

}
