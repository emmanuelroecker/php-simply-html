<?php
/**
 * Test GlHtmlNode
 *
 * PHP version 5.5
 *
 * @category  GLICER
 * @package   GlHtml\Tests
 * @author    Emmanuel ROECKER
 * @author    Rym BOUCHAGOUR
 * @copyright 2015 GLICER
 * @license   MIT
 * @link      http://dev.glicer.com/
 *
 * Created : 19/02/15
 * File : GlHtmlNodeTest.php
 *
 */

namespace GlHtml\Tests;

use GlHtml\GlHtml;
use GlHtml\GlHtmlNode;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GlHtml\GlHtmlNode
 */
class GlHtmlNodeTest extends TestCase
{

    private function removeCR($html)
    {
        return str_replace(["\n", "\r"], '', $html);
    }

    public function testSetValue()
    {
        $html       = "<!DOCTYPE html><html><head></head><body><div><span><div></div></span></div></body></html>";
        $htmlresult = "<!DOCTYPE html><html><head></head><body><div><span>bonjour</span></div></body></html>";

        $dom  = new GlHtml($html);
        $node = $dom->get("span")[0];

        $node->setValue("bonjour");

        $result = $dom->html();

        $htmlresult = $this->removeCR($htmlresult);
        $result     = $this->removeCR($result);

        $this->assertEquals($htmlresult, $result);
    }

    public function testAdd()
    {
        $html       = "<!DOCTYPE html><html><head></head><body><div><span><div></div></span></div></body></html>";
        $htmlresult = '<!DOCTYPE html><html><head></head><body><div><span><div></div><div class="super">test</div></span></div></body></html>';

        $dom  = new GlHtml($html);
        $node = $dom->get("span")[0];

        $node->add('<div class="super">test</div>');

        $result = $dom->html();

        $htmlresult = $this->removeCR($htmlresult);
        $result     = $this->removeCR($result);

        $this->assertEquals($htmlresult, $result);
    }

    public function testReplaceInner()
    {
        $html       = "<!DOCTYPE html><html><head></head><body><div><span><div></div></span></div></body></html>";
        $htmlresult = '<!DOCTYPE html><html><head></head><body><div><span><hr></span></div></body></html>';

        $dom  = new GlHtml($html);
        $node = $dom->get("span")[0];

        $node->replaceInner('<hr/>');

        $result = $dom->html();

        $htmlresult = $this->removeCR($htmlresult);
        $result     = $this->removeCR($result);

        $this->assertEquals($htmlresult, $result);
    }

    public function testGetDOMNode()
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
        $dom = new GlHtml($html);
        $node = $dom->get("p")[0];

        $this->assertInstanceOf(\DOMNode::class, $node->getDOMNode());
        $this->assertEquals('p', $node->getName());
    }

    public function testGetName()
    {
        $html = '<!DOCTYPE html><html><head></head><body><div><span id="test"><div></div></span></div></body></html>';

        $dom  = new GlHtml($html);
        $node = $dom->get("#test")[0];

        $this->assertEquals('span', $node->getName());
    }

    public function testGetAttribute()
    {
        $html = '<!DOCTYPE html><html><head></head><body><div><span id="testid" class="testclass"><div></div></span></div></body></html>';

        $dom  = new GlHtml($html);
        $node = $dom->get("#testid")[0];

        $this->assertEquals('testclass', $node->getAttribute('class'));
    }

    public function testGetText()
    {
        $html = '<!DOCTYPE html><html><head></head><body><div><span>texte<div></div></span></div></body></html>';

        $dom  = new GlHtml($html);
        $node = $dom->get("span")[0];

        $this->assertEquals('texte', $node->getText());
    }

    public function testGetSentences()
    {
        $html = '<!DOCTYPE html><html><head></head><body><div>texte1<div id="test">texte2<h1>texte3</h1>texte4</div></div></body></html>';

        $dom  = new GlHtml($html);
        $node = $dom->get("#test")[0];

        $this->assertEquals(['texte2','texte3','texte4'], $node->getSentences());
    }

    public function testCallChild()
    {
        $html = '<!DOCTYPE html><html><head></head><body><div><span><div></div></span></div></body></html>';

        $dom  = new GlHtml($html);
        $node = $dom->get("span")[0];

        $node->callChild(
             function (GlHtmlNode $childnode) {
                 $this->assertEquals('div', $childnode->getName());
             }
        );
    }

    public function testReplaceMe()
    {
        $html       = "<!DOCTYPE html><html><head></head><body><div><span><div></div></span></div></body></html>";
        $htmlresult = "<!DOCTYPE html><html><head></head><body><div><h1></h1></div></body></html>";

        $dom  = new GlHtml($html);
        $node = $dom->get("span")[0];

        $node->replaceMe("<h1></h1>");

        $result = $dom->html();

        $htmlresult = str_replace(["\n", "\r"], '', $htmlresult);
        $result     = str_replace(["\n", "\r"], '', $result);

        $this->assertEquals($htmlresult, $result);
    }


    public function testRemplaceMeParent()
    {
        $html       = "<!DOCTYPE html><html><head></head><body><div><span><div></div></span></div></body></html>";
        $htmlresult = "<!DOCTYPE html><html><head></head><body><h1></h1></body></html>";

        $dom  = new GlHtml($html);
        $node = $dom->get("span")[0];

        $node->getParent()->replaceMe("<h1></h1>");

        $result = $dom->html();

        $htmlresult = str_replace(["\n", "\r"], '', $htmlresult);
        $result     = str_replace(["\n", "\r"], '', $result);

        $this->assertEquals($htmlresult, $result);
    }

    public function testhasAttributes()
    {
        $html       = '<!DOCTYPE html><html><head></head><body><div><span data-original="test"><div class="tortue" nolazyload></div></span></div></body></html>';

        $dom = new GlHtml($html);
        $node = $dom->get("span")[0];

        $this->assertEquals($node->hasAttributes(['data-original','nolazyload']), true);

        $node = $dom->get(".tortue")[0];

        $this->assertEquals($node->hasAttributes(['nolazyload']), true);
        $this->assertEquals($node->hasAttributes(['nolazload']), false);
    }

    public function testsetAttributes()
    {
        $html       = "<!DOCTYPE html><html><head></head><body><div><span><div></div></span></div></body></html>";
        $htmlresult = '<!DOCTYPE html><html><head></head><body><div><span id="test"><div></div></span></div></body></html>';

        $dom  = new GlHtml($html);
        $node = $dom->get("span")[0];

        $node->setAttributes(['id' => 'test']);

        $result = $dom->html();

        $htmlresult = str_replace(["\n", "\r"], '', $htmlresult);
        $result     = str_replace(["\n", "\r"], '', $result);

        $this->assertEquals($htmlresult, $result);
    }

    public function testSelfClose()
    {
        $html = <<<EOD
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div><span></span></div>
</body>
</html>
EOD;

        $dom  = new GlHtml($html);
        $node = $dom->get("div")[0];

        $result     = $node->getHtml();
        $htmlresult = "<span></span>";

        $this->assertEquals($htmlresult, $result);
    }

    public function testDelete()
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
        $node->delete();

        $htmlresult = <<<EOD
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div></div>
</body>
</html>
EOD;

        $result = $dom->html();

        $result     = str_replace([' ', "\n", "\t", "\r"], '', $result);
        $htmlresult = str_replace([' ', "\n", "\t", "\r"], '', $htmlresult);
        $this->assertEquals($htmlresult, $result);
    }
}
