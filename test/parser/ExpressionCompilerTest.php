<?php

/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 29.07.16
 * Time: 01:57
 */
    
    namespace Gismo\Test\Component;

    use Html5\Template\Expression\GoExpressionEvaluator;

    class ExpressionCompilerTest extends \PHPUnit_Framework_TestCase
    {
    
    
        public function testExpressionCompiler () {
            $c = new GoExpressionEvaluator();
            $c->register("string", function ($args, $param) {return (string)$param;});
    
            print_r ($c->yaml("{wurst: someData == true, muh: string('someOther')}", ['someData'=>true]));
        }

        public function testStructAccess () {
            $data1 = new \stdClass();
            $data1->wurst = new \stdClass();
            $data1->wurst->a = "b";
            $data = [
                    "a" => [
                            "b" => "c"
                    ]
            ];
            $c = new GoExpressionEvaluator();
            self::assertEquals("c", $c->eval("a.b", $data));
        }
    
    }
