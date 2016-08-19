<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 01.08.16
     * Time: 11:52
     */

    namespace Html5\Template\Directive;
   
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\GoTemplateDirectiveBag;

    class GoCallDirective implements GoDirective
    {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->elemToDirective["go-call"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int
        {
            return 0;
        }


        private $callback;

        public function setCallback (callable  $fn) {
            $this->callback = $fn;
        }


        public function exec(GoElementNode $node, array $scope, &$output, GoDirectiveExecBag $execBag)
        {
            $name = $node->attributes["name"];

            if ( ! preg_match ("|([a-z0-9_\\.]+)\\s*\\((.*)\\)|i", trim ($name), $matches)) {
                throw new \InvalidArgumentException("Cannot parse call name='$name'");
            }

            $callName = $matches[1];
            $params = $matches[2];


            return ($this->callback)($callName, $params);
        }
    }