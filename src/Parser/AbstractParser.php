<?php

namespace FluxBB\Markdown\Parser;

use FluxBB\Markdown\Common\Text;
use FluxBB\Markdown\Node\NodeAcceptorInterface;

abstract class AbstractParser implements ParserInterface
{

    /**
     * @var NodeAcceptorInterface
     */
    protected $stack;

    /**
     * @var ParserInterface
     */
    protected $next;


    public function setNextParser(ParserInterface $next)
    {
        $this->next = $next;
    }

    public function setStack(NodeAcceptorInterface $stack)
    {
        $this->stack = $stack;
    }

    protected function splitBlocks(Text $text, $pattern, $resultHandler)
    {
        $text->handle(
            $pattern,
            $resultHandler,
            function (Text $part) {
                $this->next->parseBlock($part);
            }
        );
    }

}
