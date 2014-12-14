<?php

namespace FluxBB\Markdown\Node;

abstract class Block extends Node implements NodeAcceptorInterface
{

    protected $open = true;


    public function toString()
    {
        return ($this->isOpen() ? '-> ' : '') . parent::toString();
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
