<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 23:44
 */

    namespace Html5\Template\Directive;



    use Html5\Template\Node\GoElementNode;
    use Html5\Template\Node\GoTextNode;
    use Html5\Template\GoTemplateDirectiveBag;

    class GoBindDirective implements GoDirective {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->attrToDirective["go-bind"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int
        {
            return -999;
        }

        public function exec(GoElementNode $node, array $scope, &$output, GoDirectiveExecBag $execBag)
        {

            $expression = $node->attributes["go-bind"];

            $val = $execBag->expressionEvaluator->eval($expression, $scope);
            
            $clone = clone $node;
            if ($val !== null)
                $clone->childs = [new GoTextNode($val)];
            return $clone;
        }
    }