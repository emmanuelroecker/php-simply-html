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
        $html    = <<<EOD
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
        $dom     = new GlHtml($html);
        $summary = $dom->getSummary();

        $this->assertEquals("title1", $summary[0]->getNode()->getText());
        $this->assertEquals("subtitle1-2-1", $summary[2]->getNode()->getText());
        $this->assertEquals(1, $summary[3]->getLevel());
    }

    public function testGetSentences()
    {
        $html = <<<EOD
<!DOCTYPE html>
<html>
<head>
    <title>test de titre</title>
    <meta name="description" content="exemple de description">
</head>
<body>
<noscript class="noscript">
    <div><br><strong>JavaScript désactivé !</strong><br>

        <p>Ce site nécessite l'activation du JavaScript, veuillez l'activer dans votre navigateur Internet.</p></div>
    <p><img src="/piwik.php?idsite=4" alt=""></p></noscript>
<div style="display: none;" class="wrapper">
    <div class="loader">
        <div class="spinner">
            <div class="dot1"></div>
            <div class="dot2"></div>
        </div>
    </div>
</div>
        <div>
<p>test</p><h1>title1</h1><p>je cherche<br>une réponse<span> 1</span></p>
            <h2>subtitle1-1</h2>
                <h3>subtitle1-2-1</h3>
                <div><p>dans un text</p><p>encore un</p><p>encore deux</p></div>
        <h1>title2</h1><div><a href="http://www.glicer.com">lien direct</a></div>
        <h8>salut les <a href="toto">copains</a></h8><div>c'est top</div>
    </div>
    </body>
</html>
EOD;

        $dom       = new GlHtml($html);
        $sentences = $dom->getSentences();

        $expected[] = "test de titre";
        $expected[] = "exemple de description";
        $expected[] = "JavaScript désactivé !";
        $expected[] = "Ce site nécessite l'activation du JavaScript, veuillez l'activer dans votre navigateur Internet.";
        $expected[] = "test";
        $expected[] = "title1";
        $expected[] = "je cherche une réponse 1";
        $expected[] = "subtitle1-1";
        $expected[] = "subtitle1-2-1";
        $expected[] = "dans un text";
        $expected[] = "encore un";
        $expected[] = "encore deux";
        $expected[] = "title2";
        $expected[] = "lien direct";
        $expected[] = "salut les copains";
        $expected[] = "c'est top";

        $this->assertEquals($expected, $sentences);
    }

    public function testGetSentences2()
    {
        $html = <<<EOD
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div id="body_container" class="container">
        <div class="row">
            <div class="list-group">
                <a class="list-group-item" href="/solver/article/creer-carte-interactive.html">
                    <h1 class="list-group-item-heading">Créer, héberger et personnaliser une carte interactive</h1>
                    <p>Comment concevoir une carte géographique interactive pour un fonctionnement en ligne ou hors ligne ?</p>
                    <div class="date">26 février 2015</div>
                </a>
                <a class="list-group-item" href="/solver/article/utiliser-piwik-en-temps-differe.html">
                    <h1 class="list-group-item-heading">Utilisation de piwik en temps différé</h1>
                    <p>Comment analyser le trafic d'un site internet sans le ralentir ?</p>
                    <div class="date">17 février 2015</div>
                </a>
            </div>
        </div>
            <div class="highlight">
                <pre>
                    <span class="go">    C:\Users\GLICER\AppData\Roaming\npm\grunt -&gt; C:\Users\GLICER\AppData\Roaming\npm\node_modules\grunt-cli\bin\grunt</span>
                    <span class="go">    grunt-cli@0.1.13 C:\Users\GLICER\AppData\Roaming\npm\node_modules\grunt-cli</span>
                    <span class="go">    ├── resolve@0.3.1</span>
                    <span class="go">    ├── nopt@1.0.10 (abbrev@1.0.5)</span>
                    <span class="go">    └── findup-sync@0.1.3 (lodash@2.4.1, glob@3.2.11)</span>
                </pre>
            </div>
        </div>
</body>
</html>
EOD;

        $dom       = new GlHtml($html);
        $sentences = $dom->getSentences();

        //print_r($sentences);

        $expected[] = "Créer, héberger et personnaliser une carte interactive";
        $expected[] = "Comment concevoir une carte géographique interactive pour un fonctionnement en ligne ou hors ligne ?";
        $expected[] = "26 février 2015";
        $expected[] = "Utilisation de piwik en temps différé";
        $expected[] = "Comment analyser le trafic d'un site internet sans le ralentir ?";
        $expected[] = "17 février 2015";

        $this->assertEquals($expected, $sentences);
    }
} 