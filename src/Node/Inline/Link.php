<?php

namespace FluxBB\CommonMark\Node\Inline;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Node;
use FluxBB\CommonMark\Node\NodeVisitorInterface;

class Link extends Node
{

    /**
     * @var Text
     */
    protected $href;

    /**
     * @var Text
     */
    protected $content;

    /**
     * @var Text
     */
    protected $titleText;


    public function __construct(Text $href, Text $content = null, Text $titleText = null)
    {
        $this->href = $href;
        $this->content = $content;
        $this->titleText = $titleText ?: new Text();
    }

    /**
     * @return Text
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @return Text
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return Text
     */
    public function getTitleText()
    {
        return $this->titleText;
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
        $visitor->visitLink($this);
    }

}
