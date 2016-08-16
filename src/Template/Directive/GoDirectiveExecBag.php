<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 01.08.16
     * Time: 12:28
     */


    namespace Html5\Template\Directive;


    use Html5\Template\Expression\GoExpressionEvaluator;

    class GoDirectiveExecBag {

        public function __construct(GoExpressionEvaluator $expressionCompiler) {
            $this->expressionEvaluator = $expressionCompiler;
        }


        /**
         * @var GoExpressionEvaluator
         */
        public $expressionEvaluator;
        
        public $macros = [];

        public $scopePrototype = [];

        public $returnScope = [];
    }