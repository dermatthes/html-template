<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 22:46
 */

    namespace Html5\Template\Directive;


   

    use Html5\Template\Directive\Ex\GoBreakLoopException;
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\GoTemplateDirectiveBag;

    class GoBreakDirective implements GoDirective {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->elemToDirective["go-break"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int {
            return -1;
        }

        public function exec(GoElementNode $node, array $scope, &$output, GoDirectiveExecBag $execBag) {
            throw new GoBreakLoopException("Loop break on line {$node->lineNo}");
        }
    }