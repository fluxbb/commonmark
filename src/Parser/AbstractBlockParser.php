<?php

namespace FluxBB\CommonMark\Parser;

use FluxBB\CommonMark\InlineParser;

abstract class AbstractBlockParser implements BlockParserInterface
{

    /**
     * @var BlockParserInterface
     */
    protected $next;

    /**
     * @var BlockParserInterface
     */
    protected $first;

    /**
     * @var InlineParser
     */
    protected $inlineParser;


    public function setNextParser(BlockParserInterface $next)
    {
        $this->next = $next;
    }

    public function setFirstParser(BlockParserInterface $first)
    {
        $this->first = $first;
    }

    public function setInlineParser(InlineParser $parser)
    {
        $this->inlineParser = $parser;
    }

}
