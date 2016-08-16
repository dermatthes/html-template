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

    class GoMacroDirective implements GoDirective
    {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->elemToDirective["go-macro"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int
        {
            return 999;
        }

        public function exec(GoElementNode $node, array $scope, &$output, GoDirectiveExecBag $execBag)
        {
            $name = $node->attributes["name"];

            if ( ! preg_match ("|([a-z0-9_]+)\\s*\\((.*)\\)|i", trim ($name), $matches)) {
                throw new \InvalidArgumentException("Cannot parse macro name='$name'");
            }

            $macroName = $matches[1];
            $paramMap = [];

            $paramIndex = 0;
            foreach (explode (",", $matches[2]) as $curParam) {
                $curParam = trim ($curParam);
                $paramMap[$paramIndex++] = $curParam;
            }
            
            $execBag->macros[$macroName] = [$node, $paramMap];
            return false;
        }
    }