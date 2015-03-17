<?php
/**
 * Test GlHtmlNode
 *
 * PHP version 5.4
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

/**
 * @covers \GlHtml\GlHtmlNode
 */
class GlHtmlNodeTest extends \PHPUnit_Framework_TestCase {

    public function testDelete() {
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

        $result = str_replace([' ', "\n", "\t", "\r"], '', $result);
        $htmlresult = str_replace([' ', "\n", "\t", "\r"], '', $htmlresult);
        $this->assertEquals($htmlresult,$result);
    }
} 