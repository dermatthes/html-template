<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 17.08.16
     * Time: 12:51
     */

    namespace Html5\Template\Directive;


    use Html5\Template\GoTemplateDirectiveBag;
    use Html5\Template\Node\GoElementNode;

    class GoHtmlDirective implements GoDirective {



        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->attrToDirective["go-html"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int
        {
            return -999;
        }

        public function exec(GoElementNode $node, array $scope, &$output, GoDirectiveExecBag $execBag)
        {

            $expression = $node->attributes["go-html"];

            $val = $execBag->expressionEvaluator->eval($expression, $scope);

            $clone = clone $node;
            if ($val !== null)
                return $val;
            return $clone;
        }

    }