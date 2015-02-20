<?php
/**
 * Extend \DomNode
 *
 * PHP version 5.4
 *
 * @category  GLICER
 * @package   GlHtml
 * @author    Emmanuel ROECKER
 * @author    Rym BOUCHAGOUR
 * @copyright 2015 GLICER
 * @license   MIT
 * @link      http://www.glicer.com/solver
 *
 * Created : 20/02/15
 * File : GlHtmlSummary.php
 *
 */

namespace GlHtml;

/**
 * Class GlHtmlSummary
 * @package GlHtml
 */
class GlHtmlSummary {

    /**
     * @var int
     */
    private $level;

    /**
     * @var GlHtmlNode
     */
    private $node;

    public function __construct(GlHtmlNode $node,$level) {
        $this->node = $node;
        $this->level = $level;
    }

    /**
     * @return GlHtmlNode
     */
    public function getNode() {
        return $this->node;
    }

    /**
     * @return int
     */
    public function getLevel() {
        return $this->level;
    }

}