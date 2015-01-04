<?php

namespace FluxBB\CommonMark\Node;

class ListBlock extends Container implements NodeAcceptorInterface
{

    /**
     * @var ListItem[]
     */
    protected $items;


    public function __construct(ListItem $listItem)
    {
        $this->items = [$listItem];
        $listItem->setParent($this);
    }

    public function getItems()
    {
        return $this->items;
    }

    public function acceptParagraph(Paragraph $paragraph)
    {
        $this->addChild($paragraph);

        return $paragraph;
    }

    public function acceptBlockquote(Blockquote $blockquote)
    {
        return $this->parent->acceptBlockquote($blockquote);
    }

    public function acceptListBlock(ListBlock $listBlock)
    {
        foreach ($listBlock->items as $item) {
            $this->items[] = $item;
            $item->setParent($this);
        }

        return end($this->items);
    }

    public function acceptBlankLine(BlankLine $blankLine)
    {
        return $this->parent;
    }

    public function visit(NodeVisitorInterface $visitor)
    {
        $visitor->enterListBlock($this);

        parent::visit($visitor);

        $visitor->leaveListBlock($this);
    }

}
