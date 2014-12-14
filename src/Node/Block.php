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

    /*
     * Node acceptor methods
     */

    public function acceptParagraph(Paragraph $paragraph)
    {
        $this->close();
        return $this->parent->acceptParagraph($paragraph);
    }

    public function acceptBlockquote(Blockquote $blockquote)
    {
        $this->close();
        return $this->parent->acceptBlockquote($blockquote);
    }

    public function acceptHeading(Heading $heading)
    {
        $this->close();
        return $this->parent->acceptHeading($heading);
    }

    public function acceptHorizontalRule(HorizontalRule $horizontalRule)
    {
        $this->close();
        return $this->parent->acceptHorizontalRule($horizontalRule);
    }

    public function acceptBlankLine(BlankLine $blankLine)
    {
        $this->close();
        return $this->parent->acceptBlankLine($blankLine);
    }

    public function acceptCodeBlock(CodeBlock $codeBlock)
    {
        $this->close();
        return $this->parent->acceptCodeBlock($codeBlock);
    }

    /*
     * Node visitor methods
     */

    public function visit(NodeVisitorInterface $visitor)
    {
        foreach ($this->children as $child)
        {
            $child->visit($visitor);
        }
    }

}
