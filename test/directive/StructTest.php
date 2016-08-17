<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 17.08.16
     * Time: 09:54
     */

    namespace Gismo\Test\Component;


    use Html5\Template\GoTemplate;

    class StructTest extends \PHPUnit_Framework_TestCase {


        public function testGoStructReturnsStructuredData () {

            $t = new GoTemplate();
            $ret = $t->renderStructHtmlFile(__DIR__ . "/tpl/StructTest.html", []);

            self::assertEquals(["param1"=>"Some Data", "content"=>"Some Other"], $ret);
            print_r ($ret);

        }



    }
