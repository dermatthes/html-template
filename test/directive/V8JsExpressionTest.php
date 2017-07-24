<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 14.09.16
     * Time: 16:18
     */

    namespace Gismo\Test\Component;


    use Html5\Template\Expression\V8ExpressionEvaluator;
    use PHPUnit\Framework\TestCase;
    use SebastianBergmann\Comparator\TestClass;

    class V8JsExpressionTest extends TestCase
    {

/*
        function testScope () {
            $eval = new V8ExpressionEvaluator();
            $scope = ["cde" => "wurst"];
            var_dump($eval->eval("{abc: 'cde'}", $scope));
        }


        function testBenchmarkScope () {
            $scope = $_SERVER;


            $eval = new V8ExpressionEvaluator();

            $time = microtime(true);
            for ($i= 0; $i<1000; $i++) {
                $scope["data"] = "some $i";
                //$eval->eval("{some: data}", $scope);
                $eval->eval("'yea'", $scope);
            }
            echo "\nTime: " . (microtime(true) - $time);
        }

*/
    }
