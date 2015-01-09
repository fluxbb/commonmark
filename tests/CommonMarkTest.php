<?php

/**
 * @author Franz Liedke <franz@fluxbb.org>
 */
class CommonMarkTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider commonMarkSpecRegressions
     */
    public function testSpecRegressions($source, $expected)
    {
        $parser = new \FluxBB\CommonMark\DocumentParser();
        $renderer = new \FluxBB\CommonMark\Renderer();

        $tree = $parser->convert($source);
        $actual = $renderer->render($tree);

        $this->assertEquals($expected, $actual);
    }

    public function commonMarkSpecRegressions()
    {
        return [
            [ # Example 24
                "####### foo",
                "<p>####### foo</p>",
            ],
        ];
    }

}
