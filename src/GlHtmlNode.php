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
 * Created : 19/02/15
 * File : GlHtmlNode.php
 *
 */


namespace GlHtml;

/**
 * Class GlHtmlNode
 * @package GlHtml
 */
class GlHtmlNode
{
    /**
     * @var \DOMNode
     */
    private $node;

    /**
     * @param \DOMNode $node
     */
    public function __construct(\DOMNode $node)
    {
        $this->node = $node;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->node->setAttribute($name, $value);
        }
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->node->nodeValue = $value;
    }

    /**
     * @param string $html
     */
    public
    function add(
        $html
    ) {
        $frag = $this->node->ownerDocument->createDocumentFragment();
        $frag->appendXML($html);
        $this->node->appendChild($frag);
    }

    /**
     * @param string $html
     */
    public
    function replaceMe(
        $html
    ) {
        $frag = $this->node->ownerDocument->createDocumentFragment();
        $frag->appendXML($html);
        $this->node->parentNode->parentNode->replaceChild($frag, $this->node->parentNode);
    }

    /**
     * @param string $html
     */
    public function replaceInner($html)
    {
        $this->setValue('');
        $this->add($html);
    }


    /**
     * @return \DOMNode
     */
    public function getDOMNode()
    {
        return $this->node;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return strtolower($this->node->nodeName);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public
    function getAttribute(
        $name
    ) {
        return $this->node->getAttribute($name);
    }

    /**
     * @return string
     */
    public
    function getText()
    {
        return $this->node->nodeValue;
    }

    /**
     * @return string
     */
    public function getRenderedText()
    {
        $output = static::iterateOverNode($this->node);
        $output = preg_replace("/[ \t]*\n[ \t]*/im", "\n", $output);
        $output = trim($output);

        return $output;
    }


    public
    function delete()
    {
        $this->node->parentNode->removeChild($this->node);
    }

    /**
     * @return string
     */
    public
    function getHtml()
    {
        $innerHTML = '';
        $children  = $this->node->childNodes;
        foreach ($children as $child) {
            $innerHTML .= $child->ownerDocument->saveXML($child);
        }

        return $innerHTML;
    }

    /**
     * extract h tag from html
     *
     * @param \DOMNodeList $nodeList
     * @param callable     $fct
     */
    private function recursiveCallChild(\DOMNodeList $nodeList, callable $fct)
    {
        /**
         * @var \DOMNode $domNode
         */
        foreach ($nodeList as $domNode) {
            $fct(new GlHtmlNode($domNode));
            if ($domNode->hasChildNodes()) {
                $this->recursiveCallChild($domNode->childNodes, $fct);
            }
        }
    }

    /**
     * @param callable $fct
     */
    public function callChild(callable $fct)
    {
        $this->recursiveCallChild($this->node->childNodes, $fct);
    }

    /**
     * @param \DOMNode $node
     *
     * @return null|string
     */
    private static function nextChildName(\DOMNode $node)
    {
        $nextNode = $node->nextSibling;
        while ($nextNode != null) {
            if ($nextNode instanceof \DOMElement) {
                break;
            }
            $nextNode = $nextNode->nextSibling;
        }
        $nextName = null;
        if ($nextNode instanceof \DOMElement && $nextNode != null) {
            $nextName = strtolower($nextNode->nodeName);
        }

        return $nextName;
    }

    private static function iterateOverNode(\DOMNode $node)
    {
        if ($node instanceof \DOMText) {
            return preg_replace("/[\\t\\n\\f\\r ]+/im", " ", $node->wholeText);
        }
        if ($node instanceof \DOMDocumentType) {
            return "";
        }

        $name = strtolower($node->nodeName);
        if (preg_match('/^h(\d+)$/', $name)) {
            $name = "hx";
        }

        $nextName = static::nextChildName($node);
        if (preg_match('/^h(\d+)$/', $nextName)) {
            $nextName = "hx";
        }

        switch ($name) {
            case "hr":
                return "\n";

            case "style":
            case "head":
            case "title":
            case "meta":
            case "script":
                return "";

            case "hx":
                $output = "\n";
                break;

            case "td":
            case "th":
                $output = "\t";
                break;

            case "tr":
            case "p":
            case "div":
                $output = "\n";
                break;

            default:
                $output = "";
                break;
        }

        $childs = $node->childNodes;
        foreach ($childs as $child) {
            $output .= static::iterateOverNode($child);
        }

        switch ($name) {
            case "style":
            case "head":
            case "title":
            case "meta":
            case "script":
                return "";

            case "hx":
                $output .= "\n";
                break;

            case "p":
            case "br":
                if ($nextName != "div") {
                    $output .= "\n";
                }
                break;

            case "div":
                if (($nextName != null) && ($nextName != "div")) {
                    $output .= "\n";
                }
                break;

            case "a":
                switch ($nextName) {
                    case "hx":
                        $output .= "\n";
                        break;
                }
                break;
            default:
                break;
        }

        return $output;
    }
}
