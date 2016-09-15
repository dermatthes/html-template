<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 15.09.16
     * Time: 12:12
     */

    namespace Html5\Template\Directive;


    use Html5\Template\GoTemplateDirectiveBag;
    use Html5\Template\Node\GoElementNode;

    class GoOutputDirective implements GoDirective
    {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->elemToDirective["go-output"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int
        {
            return 0;
        }

        public function exec(GoElementNode $node, array &$scope, &$output, GoDirectiveExecBag $execBag)
        {
            $name = $node->attributes["name"];
            return $execBag->expressionEvaluator->eval($name, $scope);
        }
    }