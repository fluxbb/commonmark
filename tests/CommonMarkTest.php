<?php
use FluxBB\CommonMark\Renderer\XhtmlRenderer;

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
        $parser = new \FluxBB\CommonMark\Parser(new XhtmlRenderer());

        $actual = $parser->render($source);

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
