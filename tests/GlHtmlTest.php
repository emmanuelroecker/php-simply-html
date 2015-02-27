<?php
/**
 * Test GlHtml
 *
 * PHP version 5.4
 *
 * @category  GLICER
 * @package   GlHtml\Tests
 * @author    Emmanuel ROECKER
 * @author    Rym BOUCHAGOUR
 * @copyright 2015 GLICER
 * @license   MIT
 * @link      http://www.glicer.com/solver
 *
 * Created : 19/02/15
 * File : GlHtml.php
 *
 */
namespace GlHtml\Tests;

use GlHtml\GlHtml;

/**
 * @covers \GlHtml\GlHtml
 */
class GlHtmlTest extends \PHPUnit_Framework_TestCase
{

    public function testHtml()
    {
        $html = <<<EOD
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div><p>test</p></div>
</body>
</html>
EOD;

        $dom  = new GlHtml($html);
        $node = $dom->get("p")[0];

        $this->assertEquals("test", $node->getText());
    }

    public function testRecursiveHtml()
    {
        $html = <<<EOD
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div>
        <p>test</p>
        <h1>title1</h1>
            <p>text 1</p>
            <h2>subtitle1-1</h2>
                <h3>subtitle1-2-1</h3>
        <h1>title2</h1>
    </div>
</body>
</html>
EOD;
        $dom  = new GlHtml($html);
        $summary = $dom->getSummary();

        $this->assertEquals("title1",$summary[0]->getNode()->getText());
        $this->assertEquals("subtitle1-2-1", $summary[2]->getNode()->getText());
        $this->assertEquals(1,$summary[3]->getLevel());
    }

    public function testGetText()
    {
        $html = <<<EOD
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div>
<p>test</p><h1>title1</h1><p>je cherche<br>une réponse<span> 1</span></p>
            <h2>subtitle1-1</h2>
                <h3>subtitle1-2-1</h3>
        <h1>title2</h1><div><a href="http://www.glicer.com">lien direct</a></div>
        <h8>salut les copains</h8><div>c'est top</div>
    </div>
</body>
</html>
EOD;
        $dom  = new GlHtml($html);
        $text = $dom->getRenderedText();

        $expected = <<<EOD
test

title1

je cherche
une réponse 1

subtitle1-1

subtitle1-2-1

title2

lien direct

salut les copains

c'est top
EOD;
        $this->assertEquals($expected,$text);
    }
} 