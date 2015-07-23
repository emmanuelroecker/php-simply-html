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
 * @link      http://dev.glicer.com/
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

    public function testDiv()
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
            <p id="abstract">text 1</p>
            <h2>subtitle1-1</h2>
                <h3>subtitle1-2-1</h3>
        <h1>title2</h1>
    </div>
</body>
</html>
EOD;
        $dom     = new GlHtml($html);
        $abstract = $dom->get("#abstract")[0]->getText();

        $this->assertEquals("text 1", $abstract);
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
    <li><a role="menuitem" tabindex="-1" href="#3" target="_self">Serveur de données osm</a>
                            <ul>
                                <li><a role="menuitem" tabindex="-1" href="#4" target="_self">Pré-requis</a></li>
                                <li><a role="menuitem" tabindex="-1" href="#5" target="_self">Créer le serveur</a></li>
                                <li><a role="menuitem" tabindex="-1" href="#6" target="_self">Installer PostgreSQL</a>
                                </li>
                            </ul>
                        </li>

                         <table class="table table-condensed">
        <thead>
        <tr>
            <th>Taille originale</th>
            <th>Taille compressée</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>378 Ko</td>
            <td>214 Ko</td>
        </tr>
        </tbody>
    </table>
    <div id="body_container" class="container">
        <p><a href="http://www.w3.org/html/" target="_blank"><em>html</em></a> est un langage informatique qui permet de
            structurer le contenu des pages web. Il est le fond.</p>
        <p><a href="http://www.w3.org/Style/CSS/" target="_blank"><em>css</em></a> est un langage informatique qui permet de
            formater l'affichage des documents <em>html</em>. Il est la forme.</p>

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

        $expected[] = "Serveur de données osm";
        $expected[] = "Pré-requis";
        $expected[] = "Créer le serveur";
        $expected[] = "Installer PostgreSQL";
        $expected[] = "Taille originale";
        $expected[] = "Taille compressée";
        $expected[] = "378 Ko";
        $expected[] = "214 Ko";
        $expected[] = "html est un langage informatique qui permet de structurer le contenu des pages web. Il est le fond.";
        $expected[] = "css est un langage informatique qui permet de formater l'affichage des documents html. Il est la forme.";
        $expected[] = "Créer, héberger et personnaliser une carte interactive";
        $expected[] = "Comment concevoir une carte géographique interactive pour un fonctionnement en ligne ou hors ligne ?";
        $expected[] = "26 février 2015";
        $expected[] = "Utilisation de piwik en temps différé";
        $expected[] = "Comment analyser le trafic d'un site internet sans le ralentir ?";
        $expected[] = "17 février 2015";

        $this->assertEquals($expected, $sentences);

    }

    public function testGetSentenceForm()
    {
        $html = <<<EOD
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div class="modal-content">
        <div class="modal-header">
            <button class="close icon-animate" type="button" data-dismiss="modal" aria-hidden="true">×</button>
            <span id="feedback_modal_label" class="modal-title">Un avis ?</span></div>
            <div class="modal-body">
                <form id="feedback_form" role="form" method="post">
                    <div class="form-group">
                        <label class="sr-only" for="feedback_form_email">E-mail</label>
                        <input id="feedback_form_email" name="feedback_form_email" class="form-control" placeholder="E-Mail" required="" type="email">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="feedback_form_message">Message</label>
                        <textarea id="feedback_form_message" name="feedback_form_message" class="form-control" rows="5" placeholder="Message" required=""></textarea>
                    </div>
                    <div class="text-right">
                        <button class="btn btn-default" type="submit">Envoyer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close icon-animate" type="button" data-dismiss="modal" aria-hidden="true">×</button>
                <span id="legal_modal_label" class="modal-title">Au nom de la loi</span>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled">
                    <li>
                        <p>Site développé par Emmanuel ROECKER et Rym BOUCHAGOUR.</p>
                    </li>
                    <li>
                        <p>Sauf mention contraire, l'ensemble du contenu de ce site est la propriété exclusive de ses auteurs.</p>
                    </li>
                 </ul>
            </div>
        </div>
    </div>
</body>
</html>
EOD;

        $dom       = new GlHtml($html);
        $sentences = $dom->getSentences();

        $expected[] = "×";
        $expected[] = "Un avis ?";
        $expected[] = "E-mail";
        $expected[] = "Message";
        $expected[] = "Envoyer";
        $expected[] = "×";
        $expected[] = "Au nom de la loi";
        $expected[] = "Site développé par Emmanuel ROECKER et Rym BOUCHAGOUR.";
        $expected[] = "Sauf mention contraire, l'ensemble du contenu de ce site est la propriété exclusive de ses auteurs.";

        $this->assertEquals($expected, $sentences);
    }

    public function testLinks()
    {
        $html  = <<<EOD
<!DOCTYPE html>
<html>
<head>
    <link rel="author" href="/humans.txt">
    <link rel="shortcut icon" href="/img/favicon.ico">
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script><![endif]-->
    <test toto="/bonjour"></test>
    <test2 titi="rien/tortue"><test2>
</head>
<body>
    <li><a role="menuitem" tabindex="-1" href="#4" target="_self">Pré-requis</a></li>
    <li><a role="menuitem" tabindex="-1" href="#5" target="_self">Créer le serveur</a></li>
    <p><img src="/piwik.php?idsite=4" alt=""></p>
    <div class="container">
        <div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
            <div id="links_listgroup" class="list-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a  class="list-group-item list-group-item-link col-lg-12 col-md-12 col-sm-12 col-xs-12home" target="_blank"
                        href="http://www.abusdecine.com" data-index="0" data-title="Abus de ciné"
                        data-description="Critiques de films et actualités ciné alimentées par des cinéphiles ...">
                    <div class="pull-right"><i class="icon-news icon-animate text-danger" role="button"
                         data-sociallink="https://www.facebook.com/pages/Abus-de-cin%C3%A9/140722385941276"></i>
                    </div>
                    <h2 class="list-group-item-heading"><span class="list-group-item-title">Abus de ciné</span></h2>
                    <div class="item-description"><p>Critiques de films et actualités ciné alimentées par des cinéphiles ...</p>
                    </div>
                    <span class="tags">cinéma</span></a>
            </div>
        </div>
     </div>
     <p>un autres test http://bonjour et rien du tout</p>
     <p>        http://autretest   </p>
     <p>        http://autretest2</p>
     <p>http://autretest3</p>
</body>
</html>
EOD;
        $dom   = new GlHtml($html);
        $links = $dom->getLinks(true);

        $expected["/humans.txt"] = "/humans.txt";
        $expected["/img/favicon.ico"] = "/img/favicon.ico";
        $expected["https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"] = "https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js";
        $expected["https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"] = "https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js";
        $expected["/piwik.php?idsite=4"] = "/piwik.php?idsite=4";
        $expected["http://www.abusdecine.com"] = "http://www.abusdecine.com";
        $expected["https://www.facebook.com/pages/Abus-de-cin%C3%A9/140722385941276"] = "https://www.facebook.com/pages/Abus-de-cin%C3%A9/140722385941276";
        $expected["http://bonjour"] = "http://bonjour";
        $expected["http://autretest"] = "http://autretest";
        $expected["http://autretest2"] = "http://autretest2";
        $expected["http://autretest3"] = "http://autretest3";
        $expected["/bonjour"] = "/bonjour";
        $expected["rien/tortue"] = "rien/tortue";

        $this->assertEquals($expected, $links);
    }
} 