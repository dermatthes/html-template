<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 01.08.16
     * Time: 12:23
     */

    namespace Html5\Template\Directive;

    
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\GoTemplateDirectiveBag;

    class GoDumpDirective implements GoDirective 
    {

        public function register(GoTemplateDirectiveBag $bag) 
        {
            $bag->elemToDirective["go-dump"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int 
        {
            return 998;
        }

        public function exec(GoElementNode $node, array &$scope, &$output, GoDirectiveExecBag $execBag)
        {
            $data = $scope;
            if (isset ($node->attributes["select"])) {
                 $data = $execBag->expressionEvaluator->eval($node->attributes["select"], $scope);
            }
            return "<pre><div>Dump:</div><div>" . print_r($data, true) . "</div></pre>";
        }
    }

