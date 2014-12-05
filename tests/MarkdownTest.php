<?php

use FluxBB\Markdown\Markdown;
use FluxBB\Markdown\Renderer\HtmlRenderer;

class MarkdownTest extends PHPUnit_Framework_TestCase
{

    public function testDefaultOptions()
    {
        $markdown = new Markdown(new HtmlRenderer());

        $this->assertEquals([
            'tabWidth'       => 4,
            'nestedTagLevel' => 3,
            'strict'         => false,
            'pygments'       => false
        ], $markdown->getOptions());
    }

} 
