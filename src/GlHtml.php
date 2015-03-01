<?php
/**
 * Main Class to manipulate dom
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
 * Created : 19/02/15
 * File : GlHtml.php
 *
 */


namespace GlHtml;

use Symfony\Component\CssSelector\CssSelector;

/**
 * Class GlHtml
 * @package GlHtml
 */
class GlHtml
{
    /**
     * @var \DOMDocument
     */
    private $dom;

    /**
     * @param string $html
     */
    public function __construct($html)
    {
        $html      = static::fixNewlines($html);
        $this->dom = new \DOMDocument();

        $libxml_previous_state = libxml_use_internal_errors(true); //disable warnings
        $this->dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();
        libxml_use_internal_errors($libxml_previous_state);
    }

    /**
     * Unify newlines
     *
     * @param string $text
     *
     * @return string the fixed text
     */
    static function fixNewlines($text)
    {
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\r", "\n", $text);

        return $text;
    }

    /**
     * return one dom element with $selector css filter
     *
     * @param string $selector CSS 3 Selector
     *
     * @return GlHtmlNode[]
     */
    public function get($selector)
    {
        $xpath = new \DOMXPath($this->dom);
        $nodes = $xpath->query(CssSelector::toXPath($selector));

        $glnodes = [];
        foreach ($nodes as $node) {
            $glnodes[] = new GlHtmlNode($node);
        }

        return $glnodes;
    }

    /**
     * set a list of attributes
     *
     * @param string $selector
     * @param array  $attributes
     * @param string $value
     */
    public function set($selector, array $attributes, $value = null)
    {
        $nodes = $this->get($selector);

        foreach ($nodes as $node) {
            $node->set($attributes, $value);
        }
    }

    /**
     * @param string $selector
     */
    public function delete($selector)
    {
        $nodes = $this->get($selector);
        foreach ($nodes as $node) {
            $node->delete();
        }
    }

    /**
     * @return string
     */
    public function html()
    {
        return $this->dom->saveHTML();
    }

    public function getText()
    {
        $body = $this->get("body")[0];

        return $body->getText();
    }

    public function getSentences()
    {
        $body      = $this->get("body")[0];
        $sentences = $body->getSentences();

        $description = $this->get('meta[name="description"]')[0];
        if (isset($description)) {
            $description = trim($description->getAttribute("content"));
            if (strlen($description) > 0) {
                array_unshift($sentences, $description);
            }
        }

        $title = $this->get('title')[0];
        if (isset($title)) {
            $title = trim($title->getText());
            if (strlen($title) > 0) {
                array_unshift($sentences, $title);
            }
        }

        return $sentences;
    }

    /**
     * @return GlHtmlSummary[]
     */
    public function getSummary()
    {
        $body = $this->get("body")[0];

        $summary  = [];
        $callback = function (GlHtmlNode $childNode) use (&$summary) {
            $nodeName = $childNode->getName();

            if (preg_match('/^h(\d+)$/', $nodeName, $matches)) {
                $summary[] = new GlHtmlSummary($childNode, $matches[1]);
            }
        };

        $body->callChild($callback);

        return $summary;
    }
}