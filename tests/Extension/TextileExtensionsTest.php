<?php
use FluxBB\Markdown\Parser;
use FluxBB\Markdown\Extension\Textile\CommentExtension;
use FluxBB\Markdown\Extension\Textile\DefinitionListExtension;
use FluxBB\Markdown\Extension\Textile\HeaderExtension;
use Symfony\Component\Finder\Finder;

/**
 * Tests FluxBB\Extensions\Textile\*
 *
 * @group Textile
 *
 * @author Kazuyuki Hayashi <hayashi@siance.co.jp>
 */
class TextileExtensionsTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider textileProvider
     */
    public function testTextilePatterns($name, $textile, $expected)
    {
        $ciconia = new Parser();
        $ciconia->addExtensions([
            new HeaderExtension(),
            new DefinitionListExtension(),
            new CommentExtension()
        ]);

        $expected = str_replace("\r\n", "\n", $expected);
        $expected = str_replace("\r", "\n", $expected);
        $html     = $ciconia->render($textile);

        $this->assertEquals($expected, $html, sprintf('%s failed', $name));
    }

    /**
     * @return array
     */
    public function textileProvider()
    {
        $finder = Finder::create()
            ->in(__DIR__.'/../Resources/textile')
            ->files()
            ->name('*.textile');

        return $this->processPatterns($finder);
    }

    /**
     * @param Finder|\Symfony\Component\Finder\SplFileInfo[] $finder
     *
     * @return array
     */
    protected function processPatterns(Finder $finder)
    {
        $patterns = [];

        foreach ($finder as $file) {
            $name       = preg_replace('/\.(textile|out)$/', '', $file->getFilename());
            $expected   = trim(file_get_contents(preg_replace('/\.textile$/', '.out', $file->getPathname())));
            $expected   = str_replace("\r\n", "\n", $expected);
            $expected   = str_replace("\r", "\n", $expected);
            $patterns[] = [$name, $file->getContents(), $expected];
        }

        return $patterns;
    }

} 
