<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 01.08.16
     * Time: 16:51
     */

    namespace Html5\Template\Directive;

    
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\GoTemplateDirectiveBag;

    class GoCallMacroDirective implements GoDirective {


        public function register(GoTemplateDirectiveBag $bag) {
            $bag->elemToDirective["go-callmacro"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int {
            return 12;
        }

        public function exec(GoElementNode $node, array $scope, &$output, GoDirectiveExecBag $execBag) {
            $name = $node->attributes["name"];
            if ( ! preg_match ("|([a-z0-9_]+)\\s*\\((.*)\\)|i", trim ($name), $matches)) {
                throw new \InvalidArgumentException("Cannot parse macro name='$name'");
            }

            $macroName = $matches[1];
            $newScope = $execBag->scopePrototype;

            $paramIndexToNameMap = $execBag->macros[$macroName][1];

            $index = 0;
            foreach (explode(",", $matches[2]) as $curParam) {
                $curParam = trim($curParam);

                $newScope[$paramIndexToNameMap[$index]] = $execBag->expressionEvaluator->yaml($curParam, $scope);
                $index++;
            }

            $output = $execBag->macros[$macroName][0]->render($newScope, $execBag);
            return $output;
        }
    }