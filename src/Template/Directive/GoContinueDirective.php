<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 03.08.16
     * Time: 13:22
     */

    namespace Html5\Template\Directive;

    
    use Html5\Template\Directive\Ex\GoContinueLoopException;
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\GoTemplateDirectiveBag;

    class GoContinueDirective implements GoDirective
    {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->elemToDirective["go-continue"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int
        {
            return -1;
        }

        public function exec(GoElementNode $node, array &$scope, &$output, GoDirectiveExecBag $execBag)
        {
            throw new GoContinueLoopException("Loop continue on line {$node->lineNo}");
        }
    }
