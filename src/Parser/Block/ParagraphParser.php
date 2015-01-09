<?php

namespace FluxBB\CommonMark\Parser\Block;

use FluxBB\CommonMark\Common\Text;
use FluxBB\CommonMark\Node\Block\BlankLine;
use FluxBB\CommonMark\Node\Container;
use FluxBB\CommonMark\Node\Block\Paragraph;
use FluxBB\CommonMark\Parser\AbstractBlockParser;

class ParagraphParser extends AbstractBlockParser
{

    /**
     * Parse the given content.
     *
     * Any newly created nodes should be pushed to the stack. Any remaining content should be passed to the next parser
     * in the chain.
     *
     * @param Text $content
     * @param Container $target
     * @return void
     */
    public function parseBlock(Text $content, Container $target)
    {
        $content->handle(
            '/^\s*$/m',
            function (Text $line) use ($target) {
                $target->addChild(new BlankLine($line));
            },
            function (Text $part) use ($target) {
                $paragraph = new Paragraph($part);
                $target->addChild($paragraph);

                $this->inlineParser->queue($paragraph->getText(), $paragraph);
            }
        );
    }

}
