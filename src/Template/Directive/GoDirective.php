<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 22:42
 */

    namespace Html5\Template\Directive;
    

    use Html5\Template\Node\GoElementNode;
    use Html5\Template\GoTemplateDirectiveBag;

    interface GoDirective {

        public function register(GoTemplateDirectiveBag $bag);
        
        public function getPriority () : int;

        public function exec(GoElementNode $node, array &$scope, &$output, GoDirectiveExecBag $execBag);
    }