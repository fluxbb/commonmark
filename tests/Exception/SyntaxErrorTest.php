<?php


class SyntaxErrorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException FluxBB\CommonMark\Exception\SyntaxError
     * @expectedExceptionMessage [link] Unable to find id "id" in Reference-style link at line 3
     */
    public function testSyntaxErrorPointsRightLineNumber()
    {
        $md = new \FluxBB\CommonMark\Parser();
        $md->render(<<< EOL
This is a paragraph

This is a [paragraph][id].

<pre>
preformatted text
</pre>
EOL
, array('strict' => true)
);
    }

}
