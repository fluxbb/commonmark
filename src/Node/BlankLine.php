<?php

namespace FluxBB\Markdown\Node;

use FluxBB\Markdown\Common\Text;

class BlankLine extends Block implements NodeInterface, NodeAcceptorInterface
{

    /**
     * @var Text
     */
    protected $content;


    public function __construct(Text $content)
    {
        $this->content = $content;
    }

    public function getType()
    {
        return 'blank_line';
    }

    /**
     * @return Text
     */
    public function getContent()
    {
        return $this->content->copy();
    }

    public function canContain(Node $other)
    {
        return true;
    }

    public function proposeTo(NodeAcceptorInterface $block)
    {
        return $block->acceptBlankLine($this);
    }

}
