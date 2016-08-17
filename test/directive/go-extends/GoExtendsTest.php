<?php
    namespace Gismo\Test\Component;
    use Html5\Template\Directive\GoExtendsDirective;
    use Html5\Template\GoTemplate;

    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 17.08.16
     * Time: 11:52
     */
    class GoExtendsTest extends \PHPUnit_Framework_TestCase {


        public function testExtensWorks () {
            $g = new GoTemplate();
            $g->getDirective(GoExtendsDirective::class)
                    ->setExtendsCallback(function (string $name, array $sectionData) use ($g) {
                        return $g->renderHtmlFile(__DIR__ . "/tpl/" . $name, $sectionData); // This data is passed
                    });


            echo $g->renderHtmlFile(__DIR__ . "/tpl/extends.tpl.html");

        }



    }
