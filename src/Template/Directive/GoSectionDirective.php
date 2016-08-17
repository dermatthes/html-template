<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 17.08.16
     * Time: 09:58
     */

    namespace Html5\Template\Directive;

    use Html5\Template\Node\GoElementNode;
    use Html5\Template\GoTemplateDirectiveBag;

    class GoSectionDirective implements GoDirective
    {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->elemToDirective["go-section"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int
        {
            return 999;
        }

        public function exec(GoElementNode $node, array $scope, &$output, GoDirectiveExecBag $execBag)
        {
            $name = $node->attributes["name"];

            if ( ! preg_match ("|([a-z0-9_]+)|i", $name)) {
                throw new \InvalidArgumentException("Invalid go-section name='$name': Allowed [a-zA-Z0-9_]+");
            }

            $return = "";
            foreach ($node->childs as $curChild) {
                $return .= $curChild->run($scope, $execBag);
            }

            $execBag->dataToReturnScope[$name] = $return;
            return false;
        }
    }