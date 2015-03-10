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
     * @return array
     */
    public function getSentences()
    {
        $sentences = [];
        $sentence  = "";
        static::iterateSentencesOverNode($this->node, $sentence, $sentences);

        $sentence = trim($sentence);
        if (strlen($sentence) > 0) {
            $sentences[] = $sentence;
        }

        return $sentences;
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
     * @param string   $sentence
     * @param array    $sentences
     *
     * @return int
     */
    private static function iterateSentencesOverNode(\DOMNode $node, &$sentence, array &$sentences)
    {
        if ($node instanceof \DOMText) {
            $sentence .= preg_replace("/[\\t\\n\\f\\r ]+/im", " ", $node->wholeText);

            return 0;
        }
        if ($node instanceof \DOMDocumentType) {
            return 0;
        }

        $name = strtolower($node->nodeName);
        if (preg_match('/^h(\d+)$/', $name)) {
            $name = "hx";
        }

        switch ($name) {
            case "hr":
            case "style":
            case "head":
            case "title":
            case "meta":
            case "script":
            case "pre":
                return 0;

            case "p":
            case "hx":
            case "th":
            case "td":
            case "li":
            case "label":
            case "button":
                $sentence = trim($sentence);
                if (strlen($sentence) > 0) {
                    $sentences[] = $sentence;
                }
                $sentence = "";
                break;

            case "br":
                $sentence .= " ";
                break;
            default:
                break;
        }

        $childs = $node->childNodes;
        foreach ($childs as $child) {
            static::iterateSentencesOverNode($child, $sentence, $sentences);
        }

        switch ($name) {
            case "style":
            case "head":
            case "title":
            case "meta":
            case "script":
                return "";

            case "p":
            case "hx":
            case "th":
            case "td":
            case "li":
            case "label":
            case "button":
                $sentence = trim($sentence);
                if (strlen($sentence) > 0) {
                    $sentences[] = $sentence;
                }
                $sentence = "";
                break;

            default:
                break;
        }

        return 0;
    }
}
