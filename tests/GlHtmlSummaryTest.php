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

use GlHtml\GlHtmlNode;
use GlHtml\GlHtmlSummary;

/**
 * @covers \GlHtml\GlHtmlNode
 */
class GlHtmlSummaryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetter() {
        
        $node = new GlHtmlNode(new \DOMNode());
        $summarynode = new GlHtmlSummary($node,42);
        
        $this->assertEquals($node,$summarynode->getNode());
        $this->assertEquals(42, $summarynode->getLevel());
    }
} 