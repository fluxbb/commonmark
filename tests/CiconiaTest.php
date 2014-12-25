<?php

/**
 * @author Kazuyuki Hayashi <hayashi@siance.co.jp>
 */
class CiconiaTest extends \PHPUnit_Framework_TestCase
{

    public function testManipulateExtensions()
    {
        $md = new \FluxBB\CommonMark\Parser();
        $this->assertTrue($md->hasExtension(new \FluxBB\CommonMark\Extension\Core\EscaperExtension()));
        $this->assertFalse($md->removeExtension(new \FluxBB\CommonMark\Extension\Core\EscaperExtension())->hasExtension('escaper'));
        $this->assertTrue($md->addExtension(new \FluxBB\CommonMark\Extension\Gfm\InlineStyleExtension())->hasExtension('gfmInlineStyle'));
    }

    public function testRenderer()
    {
        $md = new \FluxBB\CommonMark\Parser(new \FluxBB\CommonMark\Renderer\XhtmlRenderer());
        $this->assertInstanceOf('FluxBB\\Markdown\\Renderer\\XhtmlRenderer', $md->getRenderer());
    }

    public function testRunTwice()
    {
        $ciconia = new \FluxBB\CommonMark\Parser();
        $markdown = file_get_contents(__DIR__.'/Resources/core/markdown-testsuite/link-idref.md');

        $this->assertEquals($ciconia->render($markdown), $ciconia->render($markdown));
    }

}
