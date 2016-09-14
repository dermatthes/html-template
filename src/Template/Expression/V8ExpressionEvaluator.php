<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 14.09.16
     * Time: 15:47
     */

    namespace Html5\Template\Expression;


    class V8ExpressionEvaluator
    {


        private $mv8;


        public function eval($code, array &$scope) {
            if ( ! isset ($this->mv8)) {
                $this->mv8 =  new \V8Js("PHP");
            }
            $__v8js = $this->mv8;

            $__v8js->scope = $scope;

            return $__v8js->executeString("for(key in PHP.scope){this[key]=PHP.scope[key];}; __return__ = {$code};", "eval({$code})", \V8Js::FLAG_FORCE_ARRAY);
        }


    }